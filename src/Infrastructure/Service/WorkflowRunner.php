<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowTransitionListener;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WorkflowRunner
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(CliValueReceiver $cliValueReceiver, ContainerInterface $container)
    {
        $this->cliValueReceiver = $cliValueReceiver;
        $this->container = $container;
    }

    /**
     * @param string $workflowName
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface|null $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(string $workflowName, ?ContextInterface $context = null): ContextInterface
    {
        $context = $context ?? new Context();

        /** @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow */
        $projectWorkflow = $this->container->get('project_workflow');

        if (!$projectWorkflow->initializeWorkflow($workflowName)) {
            $context->addMessage(
                sprintf('%s_init', $workflowName),
                new Message(
                    sprintf('Workflow `%s` can not be initialized.', $workflowName),
                    MessageInterface::ERROR,
                ),
            );

            return $context;
        }

        $metadata = $projectWorkflow->getWorkflowMetadata();
        $while = !(isset($metadata['run']) && $metadata['run'] === 'single');

        $canRerun = isset($metadata['re-run']) && $metadata['re-run'];
        if ($canRerun && $projectWorkflow->isWorkflowFinished()) {
            $projectWorkflow->restartWorkflow();
        }

        do {
            $nextTransition = $this->getNextTransition($projectWorkflow);
            if (!$nextTransition) {
                $context->addMessage(
                    sprintf('%s_%s_start', $workflowName, $nextTransition),
                    new Message(
                        sprintf('The workflow `%s` has been finished.', $workflowName),
                        MessageInterface::ERROR,
                    ),
                );

                return $context;
            }

            $context->addMessage(
                sprintf('%s_%s_apply', $workflowName, $nextTransition),
                new Message(
                    sprintf('Running transition `%s:%s` ...', $workflowName, $nextTransition),
                    MessageInterface::INFO,
                ),
            );

            $projectWorkflow->applyTransition($nextTransition, $context);

            if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
                $context->addMessage(
                    sprintf('%s_%s_fail', $workflowName, $nextTransition),
                    new Message(
                        sprintf('The `%s:%s` transition is failed, see details above.', $workflowName, $nextTransition),
                        MessageInterface::ERROR,
                    ),
                );

                return $context;
            }

            $context->addMessage(
                sprintf('%s_%s_done', $workflowName, $nextTransition),
                new Message(
                    sprintf('The `%s:%s` transition finished successfully.', $workflowName, $nextTransition),
                    MessageInterface::INFO,
                ),
            );
        } while ($while);

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     *
     * @return string|null
     */
    protected function getNextTransition(ProjectWorkflow $projectWorkflow): ?string
    {
        $nextEnabledTransitions = $projectWorkflow->getNextEnabledTransitions();

        if (count($nextEnabledTransitions) > 1) {
            $transition = $this->getPreDefinedTransition($projectWorkflow, $nextEnabledTransitions);
            if ($transition) {
                return $transition;
            }

            return $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'Select the next step in workflow.',
                    current($nextEnabledTransitions),
                    'string',
                    $nextEnabledTransitions,
                ),
            );
        }
        $nextEnabledTransition = current($nextEnabledTransitions);

        return $nextEnabledTransition ?: null;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param array<string> $nextEnabledTransitions
     *
     * @return string|null
     */
    protected function getPreDefinedTransition(ProjectWorkflow $projectWorkflow, array $nextEnabledTransitions): ?string
    {
        $runningTransition = $projectWorkflow->findPreviousTransition();
        if (!$runningTransition) {
            return null;
        }

        $runningTransitionData = $runningTransition->getData();
        if (
            isset($runningTransitionData[WorkflowTransitionListener::DATA_NEXT_TRANSITION]) &&
            in_array($runningTransitionData[WorkflowTransitionListener::DATA_NEXT_TRANSITION], $nextEnabledTransitions, true)
        ) {
            return $runningTransitionData[WorkflowTransitionListener::DATA_NEXT_TRANSITION];
        }

        return null;
    }
}
