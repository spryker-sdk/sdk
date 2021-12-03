<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Service;

use SprykerSdk\Sdk\Contracts\Entity\ContextInterface;
use SprykerSdk\Sdk\Contracts\Entity\MessageInterface;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\StagedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Presentation\Console\Commands\RunTaskWrapperCommand;
use Symfony\Component\Process\Process;

/**
 * This executor executes each stage in a different sub process
 *
 * @todo isn't that a concern of PaaS+ and should be build in their bundle?
 */
class SplitStageTaskExecutor extends TaskExecutor
{
    protected ContextRepositoryInterface $contextRepository;

    protected string $sdkDirectory;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver $placeholderResolver
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface $taskRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ContextRepositoryInterface $contextRepository
     * @param \SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface $eventLogger
     * @param iterable $commandRunners
     * @param string $sdkDirectory
     */
    public function __construct(
        PlaceholderResolver $placeholderResolver,
        TaskRepositoryInterface $taskRepository,
        ContextRepositoryInterface $contextRepository,
        EventLoggerInterface $eventLogger,
        iterable $commandRunners,
        string $sdkDirectory
    ) {
        parent::__construct($placeholderResolver, $taskRepository, $eventLogger, $commandRunners);
        $this->contextRepository = $contextRepository;
        $this->sdkDirectory = $sdkDirectory;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function executeTasks(ContextInterface $context): ContextInterface
    {
        $tasks = $context->getSubTasks();
        $stages = $context->getRequiredStages();
        $stagesCount = count($stages);
        $nextContext = $context;

        for ($i = 0; $i < $stagesCount; $i++) {
            $currentStage = $stages[$i];
            $stageContext = $this->buildStageContext($nextContext, $currentStage, $tasks);
            $stageContext->setName($context->getName() . '_' . $currentStage);
            $nextContextName = $context->getTask()->getId();

            if ($i - 1 < $stagesCount) {
                $nextContextName .= '_' . $stages[$i + 1];
            }

            $nextContext = $this->runStage($stageContext, $nextContextName);
            $nextContext->setResolvedValues($context->getResolvedValues());

            if ($nextContext->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
                return $nextContext;
            }
        }

        $context->setExitCode(ContextInterface::SUCCESS_EXIT_CODE);
        $this->contextRepository->delete($context);

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $context
     * @param string $stage
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasks
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function buildStageContext(ContextInterface $context, string $stage, array $tasks): ContextInterface
    {
        $stageContext = clone $context;
        $stageContext->setRequiredStages([$stage]);
        $stageContext->setIsDryRun(false);

        $stageContext->setSubTasks(array_filter($tasks, function (TaskInterface $task) use ($stage): bool {
            return ($task instanceof StagedTaskInterface && $task->getStage() === $stage);
        }));

        $stageContext->setRequiredPlaceholders([]);
        $stageContext = $this->collectRequiredPlaceholders($stageContext);

        $requiredPlaceholderKeys = array_map(function (PlaceholderInterface $placeholder): string {
            return $placeholder->getName();
        }, $stageContext->getRequiredPlaceholders());

        $stageContext->setResolvedValues(array_intersect_key(
            $stageContext->getResolvedValues(),
            $requiredPlaceholderKeys,
        ));

        return $stageContext;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\ContextInterface $currentContext
     * @param string $nextContextName
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\ContextInterface
     */
    protected function runStage(ContextInterface $currentContext, string $nextContextName): ContextInterface
    {
        $this->contextRepository->saveContext($currentContext);
        $call = sprintf(
            'bin/console %s --%s=%s --%s=true --%s=%s',
            $currentContext->getTask()->getId(),
            RunTaskWrapperCommand::OPTION_READ_CONTEXT_FROM,
            $currentContext->getName(),
            RunTaskWrapperCommand::OPTION_ENABLE_CONTEXT_WRITING,
            RunTaskWrapperCommand::OPTION_WRITE_CONTEXT_TO,
            $nextContextName,
        );
        $process = Process::fromShellCommandline($call, $this->sdkDirectory);
        $return = $process->run();
        $this->contextRepository->delete($currentContext);

        $nextContext = $this->contextRepository->findByName($nextContextName);

        if ($nextContext) {
            $nextContext->setExitCode($return);

            return $nextContext;
        }

        $currentContext->addMessage(new Message('Could not read context for next stage', MessageInterface::ERROR));
        $currentContext->setExitCode(ContextInterface::FAILURE_EXIT_CODE);

        return $currentContext;
    }
}
