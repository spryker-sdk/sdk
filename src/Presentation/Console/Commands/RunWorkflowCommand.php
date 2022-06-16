<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * @var string
     */
    protected const OPTION_FORCE = 'force';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected $projectWorkflow;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner
     */
    protected $workflowRunner;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner $workflowRunner
     */
    public function __construct(
        ProjectWorkflow $projectWorkflow,
        CliValueReceiver $cliValueReceiver,
        WorkflowRunner $workflowRunner
    ) {
        $this->projectWorkflow = $projectWorkflow;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->workflowRunner = $workflowRunner;
        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->addArgument(static::ARG_WORKFLOW_NAME, InputArgument::OPTIONAL, 'Workflow name');
        $this->addOption(static::OPTION_FORCE, 'f', InputOption::VALUE_NONE, 'Ignore guards and force operation to run');
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
        if (!$initializeWorkflows && !$workflowName) {
            $output->writeln('<error>You don\'t initialize any workflow.</error>');

            return static::FAILURE;
        }

        if ($workflowName && $initializeWorkflows && !in_array($workflowName, $initializeWorkflows)) {
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

        $context = $this->workflowRunner->execute($workflowName, new Context());

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
}
