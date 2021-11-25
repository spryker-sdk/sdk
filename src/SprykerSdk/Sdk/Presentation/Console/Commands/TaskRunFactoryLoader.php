<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainerInterface;

class TaskRunFactoryLoader extends ContainerCommandLoader
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected SymfonyContainerInterface $symfonyContainer;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array $commandMap
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $symfonyContainer
     */
    public function __construct(
        ContainerInterface $container,
        array $commandMap,
        SymfonyContainerInterface $symfonyContainer
    ) {
        parent::__construct($container, $commandMap);
        $this->symfonyContainer = $symfonyContainer;
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

        return new RunTaskWrapperCommand(
            $this->getTaskExecutor(),
            array_map(function (PlaceholderInterface $placeholder): InputOption {
                $valueResolver = $this->getPlaceholderResolver()->getValueResolver($placeholder);

                return new InputOption(
                    $valueResolver->getAlias() ?? $valueResolver->getId(),
                    null,
                    $placeholder->isOptional() ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED,
                    $valueResolver->getDescription(),
                );
            }, $task->getPlaceholders()),
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
     * @return \SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface
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
