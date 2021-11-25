<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use JetBrains\PhpStorm\Pure;
use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputOption;

class TaskRunFactoryLoader extends ContainerCommandLoader
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array $commandMap
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $symfonyContainer
     */
    #[Pure] public function __construct(
        ContainerInterface $container,
        array $commandMap,
        protected \Symfony\Component\DependencyInjection\ContainerInterface $symfonyContainer,
    ) {
        parent::__construct($container, $commandMap);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        if (parent::has($name)) {
            return true;
        }

        $task = $this->getTaskRepository()->findById($name);

        return ($task !== null);
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function get(string $name): Command
    {
        if (parent::has($name)) {
            return parent::get($name);
        }

        $task = $this->getTaskRepository()->findById($name);

        $options = array_map(function (PlaceholderInterface $placeholder): InputOption {
            $valueResolver = $this->getPlaceholderResolver()->getValueResolver($placeholder);

            return new InputOption(
                $valueResolver->getAlias() ?? $valueResolver->getId(),
                null,
                $placeholder->isOptional() ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED,
                $valueResolver->getDescription(),
            );
        }, $task->getPlaceholders());

        $tags = array_map(function (CommandInterface $command): array {
            return $command->getTags();
        }, $task->getCommands());
        $tags = array_unique(array_merge(...$tags));

        if (count($tags) > 0) {
            $options[] = new InputOption(
                'tags',
                't',
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Only execute subtasks that matches at least one of the given tags',
                $tags
            );
        }

        return new RunTaskWrapperCommand(
            $this->getTaskExecutor(),
            $options,
            $task->getShortDescription(),
            $task->getId(),
        );
    }

    /**
     * @return array<string>
     */
    public function getNames(): array
    {
        return array_merge(parent::getNames(), array_map(function (TaskInterface $task) {
            return $task->getId();
        }, $this->getTaskRepository()->findAll()));
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface
     */
    protected function getTaskRepository(): TaskRepositoryInterface
    {
        return $this->symfonyContainer->get('task_repository');
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected function getTaskExecutor(): TaskExecutor
    {
        return $this->symfonyContainer->get('task_executor');
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected function getPlaceholderResolver(): PlaceholderResolver
    {
        return $this->symfonyContainer->get('placeholder_resolver');
    }
}
