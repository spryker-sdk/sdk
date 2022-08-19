<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Doctrine\DBAL\Exception\TableNotFoundException;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Application\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QaAutomationCommand extends RunTaskWrapperCommand
{
    /**
     * @var string
     */
    protected const TASKS_SETTING_KEY = 'qa_tasks';

    /**
     * @var string
     */
    protected const DESCRIPTION = 'Run configurable qa tasks.';

    /**
     * @var string
     */
    protected const COMMAND_NAME = 'qa:run';

    /**
     * @var \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected TaskInterface $task;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskExecutor $taskExecutor
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextRepositoryInterface $contextRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\ContextFactory $contextFactory
     * @param \SprykerSdk\Sdk\Presentation\Console\Command\OptionExtractor $optionExtractor
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        ProjectWorkflow $projectWorkflow,
        ContextRepositoryInterface $contextRepository,
        ProjectSettingRepositoryInterface $projectSettingRepository,
        ContextFactory $contextFactory,
        OptionExtractor $optionExtractor,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->taskRepository = $taskRepository;
        try {
            $taskIds = $projectSettingRepository->getOneByPath(static::TASKS_SETTING_KEY)->getValues();
            $this->task = $this->fillTask(
                $this->taskRepository->findByIds($taskIds),
            );
            $taskOptions = $optionExtractor->extractOptions($this->task);
        } catch (TableNotFoundException $e) {
            $this->setHidden(true);
            $taskOptions = [];
        }

        parent::__construct(
            $taskExecutor,
            $projectWorkflow,
            $contextRepository,
            $projectSettingRepository,
            $contextFactory,
            $taskOptions,
            static::DESCRIPTION,
            static::COMMAND_NAME,
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->projectWorkflow->getProjectWorkflows()) {
            $output->writeln('<error>Your project has initialized workflow. Follow the workflow. See details for `sdk:workflow:run` command.</error>');

            return static::FAILURE;
        }

        $context = $this->buildContext($input);
        $context->setTask($this->task);
        $context = $this->taskExecutor->execute($context);
        $this->writeContext($input, $context);
        $this->writeFilteredMessages($output, $context);

        return $context->getExitCode();
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function fillTask(array $tasks): TaskInterface
    {
        $commands = [];
        $placeholders = [];

        foreach ($tasks as $task) {
            foreach ($task->getCommands() as $command) {
                $commands[] = new Command(
                    $command instanceof ExecutableCommandInterface || $command->getType() === 'php' ?
                        get_class($command) :
                        $command->getCommand(),
                    $command->getType(),
                    false,
                    $command->getTags(),
                    $command->getConverter(),
                    $task instanceof StagedTaskInterface ? $task->getStage() : $command->getStage(),
                    $command instanceof ErrorCommandInterface ? $command->getErrorMessage() : '',
                );
            }
            $placeholders[] = $task->getPlaceholders();
        }

        $placeholders = array_merge(...$placeholders);

        return new Task(
            static::COMMAND_NAME,
            $this->getDescription(),
            $commands,
            (new Lifecycle(
                new InitializedEventData(),
                new UpdatedEventData(),
                new RemovedEventData(),
            )),
            '0.0.1',
            $placeholders,
            '',
            null,
            false,
            ContextInterface::DEFAULT_STAGE,
            false,
            [],
        );
    }
}
