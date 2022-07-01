<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use RuntimeException;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Throwable;

class ShowWorkflowCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:workflow:show';

    /**
     * @var string
     */
    protected const ARG_WORKFLOW_NAME = 'workflow_name';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var string
     */
    protected string $sdkDirectory;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param string $sdkDirectory
     */
    public function __construct(
        ProjectWorkflow $projectWorkflow,
        CliValueReceiver $cliValueReceiver,
        string $sdkDirectory
    ) {
        $this->projectWorkflow = $projectWorkflow;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->sdkDirectory = $sdkDirectory;
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
        $io = new SymfonyStyle($input, $output);

        $workflowName = $this->getWorkflowName($input);

        $path = getenv('HOST_PWD') ?: $this->sdkDirectory;
        try {
            $dotWorkflow = $this->dumpWorkflow($workflowName);
            $fileName = $this->renderWorkflow($dotWorkflow);
            $io->text(sprintf('file://%s/%s', $path, $fileName));
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return static::FAILURE;
        }

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return string
     */
    protected function getWorkflowName(InputInterface $input): string
    {
        $workflowName = $input->getArgument(static::ARG_WORKFLOW_NAME);

        if ($workflowName) {
            return $workflowName;
        }

        $workflows = $this->projectWorkflow->getAll();

        return count($workflows) > 1
            ? $this->cliValueReceiver->receiveValue(
                new ReceiverValue(
                    'Select workflow to show',
                    current(array_keys($workflows)),
                    'string',
                    $workflows,
                ),
            )
            : current($workflows);
    }

    /**
     * @param string $workflowName
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function dumpWorkflow(string $workflowName): string
    {
        $application = $this->getApplication();
        if (!$application) {
            throw new RuntimeException('Error occurred trying to run `workflow:dump` command');
        }

        $workflowDump = new BufferedOutput();
        $application->setAutoExit(false);
        $result = $application->run(
            new ArrayInput(['command' => 'workflow:dump', 'name' => $workflowName]),
            $workflowDump,
        );

        if ($result !== static::SUCCESS) {
            throw new RuntimeException('Error occurred in `workflow:dump` command');
        }

        return $workflowDump->fetch();
    }

    /**
     * @param string $dotWorkflow
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function renderWorkflow(string $dotWorkflow): string
    {
        $dot = new Process(['dot', '-Tsvg', '-Grankdir=TB', '-oworkflow.svg'], null, null, $dotWorkflow);
        $result = $dot->run();

        if ($result !== static::SUCCESS) {
            throw new RuntimeException('Error occurred in `dot` command');
        }

        return 'workflow.svg';
    }
}
