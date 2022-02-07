<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ActionApproverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaggedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskExecutor
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var iterable<\SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface>
     */
    protected iterable $commandRunners;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected CommandExecutorInterface $commandExecutor;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator
     */
    protected ViolationReportGenerator $violationReportGenerator;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ActionApproverInterface|null
     */
    protected ?ActionApproverInterface $actionApprover;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface $commandExecutor
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator $violationReportGenerator
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ActionApproverInterface|null $actionApprover
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        TaskRepositoryInterface $taskRepository,
        CommandExecutorInterface $commandExecutor,
        ViolationReportGenerator $violationReportGenerator,
        ProjectWorkflow $projectWorkflow,
        ?ActionApproverInterface $actionApprover = null
    ) {
        $this->placeholderResolver = $placeholderResolver;
        $this->taskRepository = $taskRepository;
        $this->commandExecutor = $commandExecutor;
        $this->violationReportGenerator = $violationReportGenerator;
        $this->projectWorkflow = $projectWorkflow;
        $this->actionApprover = $actionApprover;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(string $taskId, ContextInterface $context): ContextInterface
    {
        $context = $this->addBaseTask($taskId, $context);

        if (!$this->projectWorkflow->initWorkflow($context)) {
            return $context;
        }

        $context = $this->collectTasks($context);
        $context = $this->collectAvailableStages($context);
        $context = $this->resolveInputStages($context);
        $context = $this->collectRequiredPlaceholders($context);
        $context = $this->resolveValues($context);

        $context = $this->executeTasks($context);

        $this->projectWorkflow->applyTransaction($context);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function collectRequiredPlaceholders(ContextInterface $context): ContextInterface
    {
        foreach ($context->getSubTasks() as $task) {
            foreach ($task->getPlaceholders() as $placeholder) {
                $context->addRequiredPlaceholder($placeholder);
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function resolveValues(ContextInterface $context): ContextInterface
    {
        $existingValues = $context->getResolvedValues();
        $overwrites = $context->getOverwrites();

        foreach ($context->getRequiredPlaceholders() as $requiredPlaceholder) {
            //the placeholder might already be resolved when the context was passed from a previous stage
            if (!$this->isValueResolved($requiredPlaceholder, $existingValues, $overwrites)) {
                $context->addResolvedValues(
                    $requiredPlaceholder->getName(),
                    $this->placeholderResolver->resolve($requiredPlaceholder, $context),
                );
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function executeTasks(ContextInterface $context): ContextInterface
    {
        foreach ($context->getRequiredStages() as $stage) {
            $context = $this->executeStage($context, $stage);

            if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
                return $context;
            }
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param string $stage
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function executeStage(ContextInterface $context, string $stage = ContextInterface::DEFAULT_STAGE): ContextInterface
    {
        $stageTasks = array_filter($context->getSubTasks(), function (TaskInterface $task) use ($stage): bool {
            return ($task instanceof StagedTaskInterface && $task->getStage() === $stage) || !($task instanceof StagedTaskInterface);
        });

        if (count($stageTasks) > 0) {
            $context->addMessage(
                $context->getTask()->getId(),
                new Message('Executing stage ' . $stage, MessageInterface::DEBUG),
            );
        }

        $context->setSubTasks($stageTasks);

        $commands = [];
        foreach ($stageTasks as $task) {
            if ($this->actionApprover && $task->isOptional() && !$this->actionApprover->approve(sprintf('Do you want to run this task: `%s` - %s', $task->getId(), $task->getShortDescription()))) {
                continue;
            }

            foreach ($task->getCommands() as $command) {
                $context = $this->commandExecutor->execute($command, $context, $task->getId());
                $commands[] = $command;
                if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE && $command->hasStopOnError()) {
                    return $context;
                }
            }
        }

        $this->violationReportGenerator->collectViolations($context->getTask()->getId(), $commands);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function collectTasks(ContextInterface $context): ContextInterface
    {
        $task = $context->getTask();

        if (!$task instanceof TaskSetInterface) {
            $context->setSubTasks([$task]);

            return $context;
        }

        $subTasks = array_filter($task->getSubTasks(), function (TaskInterface $task) use ($context): bool {
            if (!$task instanceof TaggedTaskInterface) {
                return false;
            }

            return count(array_intersect($task->getTags(), $context->getTags())) > 0;
        });

        if ($context->getRequiredStages() != ContextInterface::DEFAULT_STAGES) {
            $subTasks = array_filter($subTasks, function (TaskInterface $task) use ($context): bool {
                if (!$task instanceof StagedTaskInterface) {
                    return false;
                }

                return in_array($task->getStage(), $context->getRequiredStages());
            });
        }

        $context->setSubTasks($subTasks);

        return $context;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\TaskMissingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function addBaseTask(string $taskId, ContextInterface $context): ContextInterface
    {
        $baseTask = $this->taskRepository->findById($taskId, $context->getTags());

        if (!$baseTask) {
            throw new TaskMissingException(sprintf('No task with id %s found', $taskId));
        }

        $context->setTask($baseTask);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function collectAvailableStages(ContextInterface $context): ContextInterface
    {
        if (count($context->getSubTasks()) < 1) {
            $mainTask = $context->getTask();
            $availableStage = $mainTask instanceof StagedTaskInterface ? $mainTask->getStage() : ContextInterface::DEFAULT_STAGE;

            $context->setAvailableStages([$availableStage]);
            $context->setRequiredStages([$availableStage]);

            return $context;
        }

        $availableStages = array_unique(
            array_map(function (TaskInterface $subTask): string {
                if ($subTask instanceof StagedTaskInterface) {
                    return $subTask->getStage();
                }

                return ContextInterface::DEFAULT_STAGE;
            }, $context->getSubTasks()),
        );

        $context->setAvailableStages($availableStages);

        if ($context->getRequiredStages() === ContextInterface::DEFAULT_STAGES) {
            $context->setRequiredStages($context->getAvailableStages());
        }

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function resolveInputStages(ContextInterface $context): ContextInterface
    {
        $task = $context->getTask();

        $requiredStages = $context->getInputStages() ?
            array_intersect($context->getInputStages(), $context->getAvailableStages()) :
            $context->getAvailableStages();

        if (
            $task instanceof TaskSetInterface &&
            count($context->getInputStages()) === ContextInterface::SUCCESS_EXIT_CODE &&
            count($task->getStages()) !== ContextInterface::SUCCESS_EXIT_CODE
        ) {
            $requiredStages = $task->getStages();
        }

        if (!$requiredStages) {
            $context->setRequiredStages(ContextInterface::DEFAULT_STAGES);

            return $context;
        }

        $context->setRequiredStages($requiredStages);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $requiredPlaceholder
     * @param array<string, mixed> $existingValues
     * @param array<string> $overwrites
     *
     * @return bool
     */
    protected function isValueResolved(
        PlaceholderInterface $requiredPlaceholder,
        array $existingValues,
        array $overwrites
    ): bool {
        if (!array_key_exists($requiredPlaceholder->getName(), $existingValues)) {
            return false;
        }

        $valueResolver = $this->placeholderResolver->getValueResolver($requiredPlaceholder);

        if (in_array($valueResolver->getAlias(), $overwrites, true) || in_array($valueResolver->getId(), $overwrites, true)) {
            return false;
        }

        return true;
    }
}
