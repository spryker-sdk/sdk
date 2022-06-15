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
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Report\ReportGeneratorFactoryInterface;

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
     * @var \SprykerSdk\SdkContracts\Report\ReportGeneratorFactoryInterface
     */
    private ReportGeneratorFactoryInterface $reportGeneratorFactory;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ActionApproverInterface|null
     */
    protected ?ActionApproverInterface $actionApprover;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface $commandExecutor
     * @param \SprykerSdk\SdkContracts\Report\ReportGeneratorFactoryInterface $reportGeneratorFactory
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ActionApproverInterface|null $actionApprover
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        TaskRepositoryInterface $taskRepository,
        CommandExecutorInterface $commandExecutor,
        ReportGeneratorFactoryInterface $reportGeneratorFactory,
        ?ActionApproverInterface $actionApprover = null
    ) {
        $this->placeholderResolver = $placeholderResolver;
        $this->taskRepository = $taskRepository;
        $this->commandExecutor = $commandExecutor;
        $this->reportGeneratorFactory = $reportGeneratorFactory;
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
        $context = $this->collectRequiredStages($context);
        $context = $this->collectRequiredPlaceholders($context);
        $context = $this->resolveValues($context);

        return $this->executeTasks($context);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function collectRequiredStages(ContextInterface $context): ContextInterface
    {
        $task = $context->getTask();
        $commandsStages = array_unique(array_map(fn (CommandInterface $command): string => $command->getStage(), $task->getCommands()));

        if ($context->getInputStages()) {
            return $this->setContextRequiredStages($context, $context->getInputStages(), $commandsStages);
        }

        if ($task->getStages()) {
            return $this->setContextRequiredStages($context, $task->getStages(), $commandsStages);
        }

        $context->setRequiredStages($commandsStages);

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array<string> $stages
     * @param array<string> $commandsStages
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function setContextRequiredStages(ContextInterface $context, array $stages, array $commandsStages): ContextInterface
    {
        $context->setRequiredStages(
            array_intersect($stages, $commandsStages),
        );

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function collectRequiredPlaceholders(ContextInterface $context): ContextInterface
    {
        $context->setRequiredPlaceholders($context->getTask()->getPlaceholders());

        return $context;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function resolveValues(ContextInterface $context): ContextInterface
    {
        if (!$context->getRequiredStages()) {
            return $context;
        }

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
    protected function executeStage(ContextInterface $context, string $stage): ContextInterface
    {
        $task = $context->getTask();

        if (
            $this->actionApprover
            && $task->isOptional()
            && !$this->actionApprover->approve(sprintf('Do you want to run this task: `%s` - %s', $task->getId(), $task->getShortDescription()))
        ) {
            return $context;
        }

        $stageCommands = array_filter($task->getCommands(), function (CommandInterface $command) use ($stage): bool {
            return $command->getStage() === $stage;
        });

        if ($context->getTags()) {
            $stageCommands = array_filter($stageCommands, function (CommandInterface $command) use ($context): bool {
                return count(array_intersect($command->getTags(), $context->getTags())) > 0;
            });
        }

        if (count($stageCommands) > 0) {
            $context->addMessage(
                $stage,
                new Message('Executing stage: ' . $stage, MessageInterface::DEBUG),
            );
        }

        $commands = [];
        foreach ($stageCommands as $stageCommand) {
            $context = $this->commandExecutor->execute($stageCommand, $context);
            $commands[] = $stageCommand;

            if ($context->getExitCode() !== 0 && $stageCommand->hasStopOnError()) {
                return $context;
            }
        }

        $reportGenerator = $this->reportGeneratorFactory->getReportGeneratorByContext($context);

        $reportGenerator->collectReports($context->getTask()->getId(), $commands);

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
        $baseTask = $this->taskRepository->findById($taskId);

        if (!$baseTask) {
            throw new TaskMissingException(sprintf('No task with id %s found', $taskId));
        }

        $context->setTask($baseTask);

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
