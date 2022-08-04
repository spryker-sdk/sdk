<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Application\Service\TaskExecutor;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputOption;
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
     * @var \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

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
     * @var \SprykerSdk\Sdk\Core\Application\Service\ContextFactory
     */
    protected ContextFactory $contextFactory;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array<string, string> $commandMap
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface $contextRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Application\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Application\Service\ContextFactory $contextFactory
     * @param string $environment
     */
    public function __construct(
        ContainerInterface $container,
        array $commandMap,
        TaskRepositoryInterface $taskRepository,
        ContextRepositoryInterface $contextRepository,
        TaskExecutor $taskExecutor,
        PlaceholderResolver $placeholderResolver,
        ProjectSettingRepositoryInterface $projectSettingRepository,
        ProjectWorkflow $projectWorkflow,
        ContextFactory $contextFactory,
        string $environment = 'prod'
    ) {
        parent::__construct($container, $commandMap);
        $this->taskRepository = $taskRepository;
        $this->taskExecutor = $taskExecutor;
        $this->placeholderResolver = $placeholderResolver;
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

        $options = [];
        $options = $this->addPlaceholderOptions($task, $options);
        $options = $this->addTagOptions($task, $options);
        $options = $this->addStageOptions($task, $options);
        $options = $this->addContextOptions($options);

        $command = new RunTaskWrapperCommand(
            $this->taskExecutor,
            $this->projectWorkflow,
            $this->contextRepository,
            $this->projectSettingRepository,
            $this->contextFactory,
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
            }, $this->taskRepository->findAll()));
        } catch (Throwable $throwable) {
            //When the SDK is not initialized tasks can't be loaded from the DB but the symfony console still
            //need to be executable to make the init:sdk command available
            return parent::getNames();
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addTagOptions(TaskInterface $task, array $options): array
    {
        $tags = [];

        foreach ($task->getCommands() as $command) {
            $tags[] = $command->getTags();
        }
        $tags = array_merge(...$tags);

        if (count($tags) > 0) {
            $options[] = new InputOption(
                RunTaskWrapperCommand::OPTION_TAGS,
                substr(RunTaskWrapperCommand::OPTION_TAGS, 0, 1),
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Only execute subtasks that matches at least one of the given tags',
                array_unique($tags),
            );
        }

        return $options;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addStageOptions(TaskInterface $task, array $options): array
    {
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_STAGES,
            substr(RunTaskWrapperCommand::OPTION_STAGES, 0, 1),
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            'Only execute subtasks that matches at least one of the given stages',
            [],
        );

        return $options;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addPlaceholderOptions(TaskInterface $task, array $options): array
    {
        foreach ($task->getPlaceholders() as $placeholder) {
            $valueResolver = $this->placeholderResolver->getValueResolver($placeholder);

            $options[] = new InputOption(
                $valueResolver->getAlias() ?? $valueResolver->getId(),
                null,
                $placeholder->isOptional() ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED,
                $valueResolver->getDescription(),
            );
        }

        return $options;
    }

    /**
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addContextOptions(array $options): array
    {
        $defaultContextFilePath = getcwd() . DIRECTORY_SEPARATOR . 'sdk.context.json';

        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_READ_CONTEXT_FROM,
            null,
            InputOption::VALUE_OPTIONAL,
            'Read the context from given JSON file. Can be overwritten via additional options',
            null,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_ENABLE_CONTEXT_WRITING,
            null,
            InputOption::VALUE_OPTIONAL,
            'Enable serializing the context into a file',
            false,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_WRITE_CONTEXT_TO,
            null,
            InputOption::VALUE_OPTIONAL,
            'Current context will be written to the given filepath in JSON format',
            $defaultContextFilePath,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_DRY_RUN,
            'd',
            InputOption::VALUE_OPTIONAL,
            'Will only simulate a run and not execute any of the commands',
            false,
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_OVERWRITES,
            'o',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Will allow to overwrite values that are already passed inside the context',
            [],
        );
        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_FORMAT,
            null,
            InputOption::VALUE_OPTIONAL,
            'Set format for violations report',
            null,
        );

        return $options;
    }
}
