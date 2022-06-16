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

        $initializedWorkflows = $this->projectWorkflow->findInitializedWorkflows();
        if (!$initializedWorkflows) {
            $output->writeln('<error>You haven\'t initialized any workflow.</error>');

            return static::FAILURE;
        }

        if ($workflowName && !in_array($workflowName, $initializedWorkflows)) {
            $output->writeln(sprintf('<error>The `%s` workflow hasn\'t been initialized.</error>', $workflowName));

            return static::FAILURE;
        }

        if (!$workflowName) {
            $workflowName = count($initializedWorkflows) > 1 ? $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'You have more than one initialized workflow. You have to select one.',
                    current(array_keys($initializedWorkflows)),
                    'string',
                    $initializedWorkflows,
                ),
            ) : current($initializedWorkflows);
        }

        $context = new Context();
        $this->projectWorkflow->initializeWorkflow($workflowName);

        $metadata = $this->projectWorkflow->getWorkflowMetadata();
        $while = !(isset($metadata['run']) && $metadata['run'] === 'single');

        $previousEnabledTransition = null;
        do {
            $nextEnabledTransition = $this->getNextTransition();
            if (!$nextEnabledTransition) {
                $output->writeln(sprintf('<error> The workflow `%s` has been finished.</error>.</error>', $workflowName));

                return static::FAILURE;
            }

            if ($previousEnabledTransition === $nextEnabledTransition) {
                break;
            }
            $previousEnabledTransition = $nextEnabledTransition;

            $this->projectWorkflow->applyTransition($nextEnabledTransition, $context);
            $output->writeln(sprintf('<info>Running task `%s` ...</info>', $nextEnabledTransition));

            if ($context->getExitCode() === 1) {
                $this->writeFilteredMessages($output, $context);
                $output->writeln(sprintf('<error>The `%s` task failed, see details above.</error>', $nextEnabledTransition));

                return static::FAILURE;
            }

            $output->writeln(sprintf('<info>The `%s` task finished successfully.</info>', $nextEnabledTransition));
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
     * @return string|null
     */
    protected function getNextTransition(): ?string
    {
        $nextEnabledTransition = $this->projectWorkflow->getNextEnabledTransition();

        if (count($nextEnabledTransition) > 1) {
            return $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'Select the next step in workflow.',
                    current($nextEnabledTransition),
                    'string',
                    $nextEnabledTransition,
                ),
            );
        }
        $nextEnabledTransition = current($nextEnabledTransition);

        return $nextEnabledTransition ?: null;
    }
}
