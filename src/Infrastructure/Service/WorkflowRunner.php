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

        do {
            $nextEnabledTransaction = $this->getNextTransaction($projectWorkflow);
            if (!$nextEnabledTransaction) {
                $context->addMessage(
                    sprintf('%s_%s_start', $workflowName, $nextEnabledTransaction),
                    new Message(
                        sprintf('Workflow `%s` has been finished.', $workflowName),
                        MessageInterface::ERROR,
                    ),
                );

                return $context;
            }

            $projectWorkflow->applyTransaction($nextEnabledTransaction, $context);

            $context->addMessage(
                sprintf('%s_%s_apply', $workflowName, $nextEnabledTransaction),
                new Message(
                    sprintf('Running task `%s` ...', $nextEnabledTransaction),
                    MessageInterface::INFO,
                ),
            );

            if ($context->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE) {
                $context->addMessage(
                    sprintf('%s_%s_fail', $workflowName, $nextEnabledTransaction),
                    new Message(
                        sprintf('The `%s` task is failed, see details above.', $nextEnabledTransaction),
                        MessageInterface::ERROR,
                    ),
                );

                return $context;
            }

            $context->addMessage(
                sprintf('%s_%s_done', $workflowName, $nextEnabledTransaction),
                new Message(
                    sprintf('The `%s` task successfully done.', $nextEnabledTransaction),
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
    protected function getNextTransaction(ProjectWorkflow $projectWorkflow): ?string
    {
        $nextEnabledTransactions = $projectWorkflow->getNextEnabledTransactions();

        if (count($nextEnabledTransactions) > 1) {
            return $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'Select the next step in workflow.',
                    current($nextEnabledTransactions),
                    'string',
                    $nextEnabledTransactions,
                ),
            );
        }
        $nextEnabledTransaction = current($nextEnabledTransactions);

        return $nextEnabledTransaction ?: null;
    }
}
