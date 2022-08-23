<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Application\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Service\TaskOptionBuilder;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Throwable;

class TaskRunFactoryLoader extends ContainerCommandLoader
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor
     */
    protected TaskExecutor $taskExecutor;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskOptionBuilder
     */
    protected TaskOptionBuilder $taskOptionBuilder;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var string
     */
    protected string $environment;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface
     */
    protected ContextRepositoryInterface $contextRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array<string, string> $commandMap
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface $contextRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskOptionBuilder $taskOptionBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     * @param string $environment
     */
    public function __construct(
        ContainerInterface $container,
        array $commandMap,
        TaskRepositoryInterface $taskRepository,
        ContextRepositoryInterface $contextRepository,
        TaskExecutor $taskExecutor,
        TaskOptionBuilder $taskOptionBuilder,
        ProjectSettingRepositoryInterface $projectSettingRepository,
        ProjectWorkflow $projectWorkflow,
        ContextFactoryInterface $contextFactory,
        string $environment = 'prod'
    ) {
        parent::__construct($container, $commandMap);
        $this->taskRepository = $taskRepository;
        $this->taskExecutor = $taskExecutor;
        $this->taskOptionBuilder = $taskOptionBuilder;
        $this->projectSettingRepository = $projectSettingRepository;
        $this->contextRepository = $contextRepository;
        $this->projectWorkflow = $projectWorkflow;
        $this->environment = $environment;
        $this->contextFactory = $contextFactory;
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

        $task = $this->taskRepository->findById($name);

        return ($task !== null);
    }

    /**
     * @param string $name
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskMissingException
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function get(string $name): Command
    {
        if (parent::has($name)) {
            return parent::get($name);
        }

        $task = $this->taskRepository->findById($name);

        if (!$task) {
            throw new TaskMissingException('Could not find task ' . $name);
        }

        $command = new RunTaskWrapperCommand(
            $this->taskExecutor,
            $this->projectWorkflow,
            $this->contextRepository,
            $this->projectSettingRepository,
            $this->contextFactory,
            $this->taskOptionBuilder->extractOptions($task),
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
            }, $this->taskRepository->findAll()));
        } catch (Throwable $throwable) {
            //When the SDK is not initialized tasks can't be loaded from the DB but the symfony console still
            //need to be executable to make the init:sdk command available
            return parent::getNames();
        }
    }
}
