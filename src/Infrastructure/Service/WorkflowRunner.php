<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Infrastructure\Event\InputOutputReceiverInterface;
use SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowTransitionListener;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WorkflowRunner implements InputOutputReceiverInterface
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $cliValueReceiver;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $cliValueReceiver
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     */
    public function __construct(
        InteractionProcessorInterface $cliValueReceiver,
        ContainerInterface $container,
        ContextFactoryInterface $contextFactory
    ) {
        $this->cliValueReceiver = $cliValueReceiver;
        $this->container = $container;
        $this->contextFactory = $contextFactory;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return void
     */
    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * @param string $workflowName
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface|null $context
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(string $workflowName, ?ContextInterface $context = null): ContextInterface
    {
        $context = $context ?? $this->contextFactory->getContext();

        /** @var \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow */
        $projectWorkflow = $this->container->get('project_workflow');

        if (!$projectWorkflow->initializeWorkflow($workflowName)) {
            $this->output->writeln(
                sprintf('<error>Workflow `%s` can not be initialized.</error>', $workflowName),
                OutputInterface::VERBOSITY_NORMAL,
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
                $this->output->writeln(
                    sprintf('<error>The workflow `%s` has been finished.</error>', $workflowName),
                    OutputInterface::VERBOSITY_NORMAL,
                );

                return $context;
            }

            $this->output->writeln(
                sprintf('<info>Applying transition `%s:%s`.</info>', $workflowName, $nextTransition),
                OutputInterface::VERBOSITY_VERY_VERBOSE,
            );

            $projectWorkflow->applyTransition($nextTransition, $context);

            if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
                $this->output->writeln(
                    sprintf(
                        '<error>The `%s:%s` transition is failed, see details above.</error>',
                        $workflowName,
                        $nextTransition,
                    ),
                    OutputInterface::VERBOSITY_NORMAL,
                );

                return $context;
            }

            $this->output->writeln(
                sprintf('<info>The `%s:%s` transition finished successfully.</info>', $workflowName, $nextTransition),
                OutputInterface::VERBOSITY_VERY_VERBOSE,
            );
        } while ($while);

        return $context;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
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
                    ValueTypeEnum::TYPE_STRING,
                    $nextEnabledTransitions,
                ),
            );
        }
        $nextEnabledTransition = current($nextEnabledTransitions);

        return $nextEnabledTransition ?: null;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
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
