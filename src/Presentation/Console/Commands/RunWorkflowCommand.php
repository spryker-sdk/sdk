<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunWorkflowCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:workflow:run';

    /**
     * @var string
     */
    protected const ARG_WORKFLOW_NAME = 'workflow_name';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected $projectWorkflow;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected $taskExecutor;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     */
    public function __construct(
        ProjectWorkflow $projectWorkflow,
        CliValueReceiver $cliValueReceiver,
        TaskExecutor $taskExecutor
    ) {
        $this->projectWorkflow = $projectWorkflow;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->taskExecutor = $taskExecutor;
        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->addArgument(static::ARG_WORKFLOW_NAME, InputArgument::OPTIONAL, 'Workflow name');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $workflowName = $input->getArgument(static::ARG_WORKFLOW_NAME);

        $initializeWorkflows = $this->projectWorkflow->findInitializeWorkflows();
        if (!$initializeWorkflows) {
            $output->writeln('<error>You don\'t initialize any workflow.</error>');

            return static::FAILURE;
        }

        if ($workflowName && !in_array($workflowName, $initializeWorkflows)) {
            $output->writeln(sprintf('<error>You don\'t initialize `%s` workflow.</error>', $workflowName));

            return static::FAILURE;
        }

        if (!$workflowName) {
            $workflowName = count($initializeWorkflows) > 1 ? $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'You have more then one workflow. you have to select the one.',
                    current(array_keys($initializeWorkflows)),
                    'string',
                    $initializeWorkflows,
                ),
            ) : current($initializeWorkflows);
        }

        $currentTask = null;
        $context = new Context();
        $metadata = $this->projectWorkflow->getWorkflowMetadata($workflowName);
        $while = !empty($metadata['run']) && $metadata['run'] === 'single' ? false : true;

        do {
            $enabledTasksIds = $this->projectWorkflow->getWorkflowTasks($workflowName);

            if (!$enabledTasksIds) {
                $output->writeln('<error>You finished current workflow or this workflow is empty.</error>');

                return static::FAILURE;
            }

            $taskId = $this->getTaskId($enabledTasksIds);
            if ($currentTask === $taskId) {
                break;
            }
            $currentTask = $taskId;
            $flippedTaskIds = array_flip($enabledTasksIds);
            $output->writeln(sprintf('<info>Running task `%s` ...</info>', $flippedTaskIds[$currentTask]));
            $context = $this->taskExecutor->execute($currentTask, $context);

            if ($context->getExitCode() === 1) {
                $this->writeFilteredMessages($output, $context);
                $output->writeln(sprintf('<error>The `%s` task is failed, see details above.</error>', $flippedTaskIds[$currentTask]));

                return static::FAILURE;
            }

            $output->writeln(sprintf('<info>The `%s` task successfully done.</info>', $flippedTaskIds[$currentTask]));
        } while ($while);
        $this->writeFilteredMessages($output, $context);

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return void
     */
    protected function writeFilteredMessages(
        OutputInterface $output,
        ContextInterface $context
    ): void {
        foreach ($context->getMessages() as $message) {
            $output->writeln($this->formatMessage($message));
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\MessageInterface $message
     *
     * @return string
     */
    protected function formatMessage(MessageInterface $message): string
    {
        return match ($message->getVerbosity()) {
            MessageInterface::INFO => '<info>Info: ' . $message->getMessage() . '</info>',
            MessageInterface::ERROR => '<error>Error: ' . $message->getMessage() . '</error>',
            MessageInterface::SUCCESS => '<fg=black;bg=green>Success: ' . $message->getMessage() . '</>',
            MessageInterface::DEBUG => '<fg=black;bg=yellow>Debug: ' . $message->getMessage() . '</>',
            default => $message->getMessage(),
        };
    }

    /**
     * @param array $enabledTasksIds
     *
     * @return string
     */
    protected function getTaskId(array $enabledTasksIds): string
    {
        if (count($enabledTasksIds) > 1) {
            $answer = $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'Select the next task in workflow.',
                    array_key_first($enabledTasksIds),
                    'string',
                    array_keys($enabledTasksIds),
                ),
            );

            return $enabledTasksIds[$answer];
        }

        return current($enabledTasksIds);
    }
}
