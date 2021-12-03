<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Psr\Container\ContainerInterface;
use SprykerSdk\Sdk\Contracts\Entity\ContextInterface;
use SprykerSdk\Sdk\Contracts\Entity\StagedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaggedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskSetInterface;
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

    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array<string, string> $commandMap
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     */
    public function __construct(
        ContainerInterface $container,
        array $commandMap,
        TaskRepositoryInterface $taskRepository,
        TaskExecutor $taskExecutor,
        PlaceholderResolver $placeholderResolver
    ) {
        parent::__construct($container, $commandMap);
        $this->taskRepository = $taskRepository;
        $this->taskExecutor = $taskExecutor;
        $this->placeholderResolver = $placeholderResolver;
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

        $options = [];
        $options = $this->addPlaceholderOptions($task, $options);
        $options = $this->addTagOptions($task, $options);
        $options = $this->addStageOptions($task, $options);
        $options = $this->addContextOptions($options);

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
        try {
            return array_merge(parent::getNames(), array_map(function (TaskInterface $task) {
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

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addTagOptions(TaskInterface $task, array $options): array
    {
        $tags = [];

        if ($task instanceof TaggedTaskInterface) {
            $tags = array_merge($tags, $task->getTags());
        }

        if ($task instanceof TaskSetInterface) {
            foreach ($task->getSubTasks() as $taskSetTask) {
                if ($taskSetTask instanceof TaggedTaskInterface) {
                    $tags = array_merge($tags, $taskSetTask->getTags());
                }
            }
        }

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
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addStageOptions(TaskInterface $task, array $options): array
    {
        $stages = [];

        if ($task instanceof StagedTaskInterface) {
            $stages = $task->getStage();
        }

        if ($task instanceof TaskSetInterface) {
            foreach ($task->getSubTasks() as $taskSetTask) {
                if ($taskSetTask instanceof StagedTaskInterface) {
                    $stages[] = $taskSetTask->getStage();
                }
            }
        }

        $options[] = new InputOption(
            RunTaskWrapperCommand::OPTION_STAGES,
            substr(RunTaskWrapperCommand::OPTION_STAGES, 0, 1),
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            'Only execute subtasks that matches at least one of the given stages',
            !empty($stages) ? array_unique($stages) : ContextInterface::DEFAULT_STAGES,
        );

        return $options;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param array<\Symfony\Component\Console\Input\InputOption> $options
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    protected function addPlaceholderOptions(TaskInterface $task, array $options): array
    {
        foreach ($task->getPlaceholders() as $placeholder) {
            $valueResolver = $this->getPlaceholderResolver()->getValueResolver($placeholder);

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
        $defaultContextFilePath = getcwd() . DIRECTORY_SEPARATOR . 'sdk-context.json';

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

        return $options;
    }
}
