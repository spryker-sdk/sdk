<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Infrastructure\Workflow\WorkflowRunner;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
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
     * @var string|null The default command description
     */
    protected static $defaultDescription = 'Run one of available workflows.';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Workflow\WorkflowRunner
     */
    protected WorkflowRunner $workflowRunner;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface
     */
    protected SettingFetcherInterface $settingFetcher;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $cliValueReceiver
     * @param \SprykerSdk\Sdk\Infrastructure\Workflow\WorkflowRunner $workflowRunner
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface $settingFetcher
     */
    public function __construct(
        ProjectWorkflow $projectWorkflow,
        InteractionProcessorInterface $cliValueReceiver,
        WorkflowRunner $workflowRunner,
        ContextFactoryInterface $contextFactory,
        SettingFetcherInterface $settingFetcher
    ) {
        $this->projectWorkflow = $projectWorkflow;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->workflowRunner = $workflowRunner;
        $this->contextFactory = $contextFactory;
        $this->settingFetcher = $settingFetcher;

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
        $this->setHelp(
            <<<EOT
Documentation on workflows is available at https://github.com/spryker-sdk/sdk/blob/master/docs/workflow.md
EOT,
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
        /** @var string|null $workflowName */
        $workflowName = $input->getArgument(static::ARG_WORKFLOW_NAME);
        $projectWorkflows = (array)$this->settingFetcher->getOneByPath(Setting::PATH_WORKFLOW)->getValues();

        if ($workflowName && !in_array($workflowName, $projectWorkflows)) {
            $output->writeln('<error>You don\'t have any active a workflows".</error>');

            return static::FAILURE;
        }

        if (!$workflowName) {
            $workflows = $projectWorkflows ?: $this->projectWorkflow->getProjectWorkflows() ?: $this->projectWorkflow->getAll();
            $workflowName = count($workflows) > 1 ? $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'You have more than one initialized workflow. You have to select one.',
                    current(array_keys($workflows)),
                    ValueTypeEnum::TYPE_STRING,
                    $workflows,
                ),
            ) : current($workflows);
        }
        $context = $this->contextFactory->getContext();

        $context = $this->workflowRunner->execute($workflowName, $context);

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
        $template = [
                MessageInterface::INFO => '<info>Info: %s</info>',
                MessageInterface::ERROR => '<error>Error: %s</error>',
                MessageInterface::SUCCESS => '<fg=black;bg=green>Success: %s</>',
                MessageInterface::DEBUG => '<fg=black;bg=yellow>Debug: %s</>',
            ][$message->getVerbosity()] ?? '%s';

        return sprintf($template, $message->getMessage());
    }
}
