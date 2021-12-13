<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainerInterface;
use Throwable;

class TaskRunFactoryLoader extends ContainerCommandLoader
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected SymfonyContainerInterface $symfonyContainer;

    /**
     * @var \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected TaskExecutor $taskExecutor;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    private string $environment;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array<string, string> $commandMap
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param string $environment
     */
    public function __construct(
        ContainerInterface $container,
        array $commandMap,
        TaskRepositoryInterface $taskRepository,
        TaskExecutor $taskExecutor,
        PlaceholderResolver $placeholderResolver,
        string $environment = 'prod'
    ) {
        parent::__construct($container, $commandMap);
        $this->taskRepository = $taskRepository;
        $this->taskExecutor = $taskExecutor;
        $this->placeholderResolver = $placeholderResolver;
        $this->environment = $environment;
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
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function get(string $name): Command
    {
        if (parent::has($name)) {
            return parent::get($name);
        }

        $task = $this->getTaskRepository()->findById($name);

        if (!$task) {
            throw new TaskMissingException('Could not find task ' . $name);
        }

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
                $tags,
            );
        }

        $command = new RunTaskWrapperCommand(
            $this->getTaskExecutor(),
            $options,
            $task->getShortDescription(),
            $task->getId(),
        );

        if (!$command->getHelp()) {
            $command->setHelp((string)$task->getHelp());
        }

        return $command;
    }

    /**
     * @return array<string>
     */
    public function getNames(): array
    {
        try {
            $symfonyCommands = parent::getNames();

            if ($this->environment === 'prod') {
                $allowedCommands = ['list', 'help'];
                $symfonyCommands = array_filter($symfonyCommands, function (string $commandName) use ($allowedCommands): bool {
                    if (in_array($commandName, $allowedCommands)) {
                        return true;
                    }

                    return preg_match('/^sdk:/', $commandName) >= 1;
                });
            }

            return array_merge($symfonyCommands, array_map(function (TaskInterface $task) {
                return $task->getId();
            }, $this->getTaskRepository()->findAll()));
        } catch (Throwable $exception) {
            //When the SDK is not initialized tasks can't be loaded from the DB but the symfony console still
            //need to be executable to make the init:sdk command available
            return parent::getNames();
        }
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface
     */
    protected function getTaskRepository(): TaskRepositoryInterface
    {
        return $this->taskRepository;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected function getTaskExecutor(): TaskExecutor
    {
        return $this->taskExecutor;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected function getPlaceholderResolver(): PlaceholderResolver
    {
        return $this->placeholderResolver;
    }
}
