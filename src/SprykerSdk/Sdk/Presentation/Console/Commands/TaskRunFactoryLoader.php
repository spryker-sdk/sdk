<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputOption;

class TaskRunFactoryLoader extends ContainerCommandLoader
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param \Symfony\Component\Console\CommandLoader\ContainerCommandLoader $baseContainerCommandLoader
     */
    public function __construct(
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
     * @return \SprykerSdk\Sdk\Presentation\Console\Commands\RunTaskWrapperCommand
     */
    public function get(string $name)
    {
        if (parent::has($name)) {
            return parent::get($name);
        }

        $task = $this->getTaskRepository()->findById($name);

        return new RunTaskWrapperCommand(
            $this->getLocalCliRunner(),
            $this->getCliValueReceiver(),
            $this->getTaskExecutor(),
            array_map(function (Placeholder $placeholder): InputOption {
                $valueResolver = $this->getPlaceholderResolver()->getValueResolver($placeholder);

                return new InputOption(
                    $valueResolver->getAlias() ?? $valueResolver->getId(),
                    null,
                    $placeholder->isOptional ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED,
                    $valueResolver->getDescription()
                );
            }, $task->placeholders),
            $task->id
        );
    }

    /**
     * @return array<string>
     */
    public function getNames(): array
    {
        return array_merge(parent::getNames(), array_map(function (Task $task) {
            return $task->id;
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
     * @return \SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner
     */
    protected function getLocalCliRunner(): LocalCliRunner
    {
        return $this->symfonyContainer->get('local_cli_command_runner');
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected function getCliValueReceiver(): CliValueReceiver
    {
        return $this->symfonyContainer->get('cli_value_receiver');
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