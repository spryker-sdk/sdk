<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;
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
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
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

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory
     */
    protected ReportFormatterFactory $reportFormatterFactory;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var string
     */
    private string $environment;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface
     */
    protected ContextRepositoryInterface $contextRepository;

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array<string, string> $commandMap
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface $contextRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory $reportFormatterFactory
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param string $environment
     */
    public function __construct(
        ContainerInterface $container,
        array $commandMap,
        TaskRepositoryInterface $taskRepository,
        ContextRepositoryInterface $contextRepository,
        TaskExecutor $taskExecutor,
        PlaceholderResolver $placeholderResolver,
        ReportFormatterFactory $reportFormatterFactory,
        ProjectWorkflow $projectWorkflow,
        string $environment = 'prod'
    ) {
        parent::__construct($container, $commandMap);
        $this->taskRepository = $taskRepository;
        $this->taskExecutor = $taskExecutor;
        $this->placeholderResolver = $placeholderResolver;
        $this->reportFormatterFactory = $reportFormatterFactory;
        $this->contextRepository = $contextRepository;
        $this->projectWorkflow = $projectWorkflow;
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

        $task = $this->taskRepository->findById($name);

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
            $this->reportFormatterFactory,
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
        } catch (Throwable) {
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

        if ($task instanceof TaskSetInterface) {
            foreach ($task->getSubTasks() as $subTask) {
                $options = $this->addPlaceholderOptions($subTask, $options);
            }
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
