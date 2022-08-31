<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use RuntimeException;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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
     * @var string|null The default command description
     */
    protected static $defaultDescription = 'Render workflow as SVG.';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var string
     */
    protected string $varWorkflowDirectory;

    /**
     * @var string
     */
    protected string $hostVarWorkflowDirectory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow $projectWorkflow
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver $cliValueReceiver
     * @param string $varWorkflowDirectory
     * @param string $hostVarWorkflowDirectory
     */
    public function __construct(
        ProjectWorkflow $projectWorkflow,
        CliValueReceiver $cliValueReceiver,
        string $varWorkflowDirectory,
        string $hostVarWorkflowDirectory
    ) {
        parent::__construct(static::NAME);

        $this->projectWorkflow = $projectWorkflow;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->varWorkflowDirectory = $varWorkflowDirectory;
        $this->hostVarWorkflowDirectory = $hostVarWorkflowDirectory;
    }

    /**
     * @return void
     */
    protected function configure(): void
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
        $workflowName = $this->getWorkflowName($input);

        $dotWorkflow = $this->dumpWorkflow($workflowName);

        $filePath = $this->renderWorkflow($workflowName, $dotWorkflow);

        $this->writeWorkflowFileConsoleMessage($output, $filePath);

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
            return is_array($workflowName) ? current($workflowName) : $workflowName;
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
     * @param string $workflowName
     * @param string $dotWorkflow
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function renderWorkflow(string $workflowName, string $dotWorkflow): string
    {
        $process = new Process(['dot', '-Tsvg', '-Grankdir=TB', '-o' . $this->getWorkflowGraphTargetFileName($workflowName)], null, null, $dotWorkflow);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException(sprintf('Error occurred in `dot` command: %s', $process->getErrorOutput()));
        }

        return $this->getWorkflowGraphHostFileName($workflowName);
    }

    /**
     * @param string $workflowName
     *
     * @return string
     */
    protected function getWorkflowGraphTargetFileName(string $workflowName): string
    {
        if (!is_dir($this->varWorkflowDirectory)) {
            mkdir($this->varWorkflowDirectory, 0766, true);
        }

        return sprintf('%s/%s.svg', $this->varWorkflowDirectory, $workflowName);
    }

    /**
     * @param string $workflowName
     *
     * @return string
     */
    protected function getWorkflowGraphHostFileName(string $workflowName): string
    {
        return sprintf('%s/%s.svg', $this->hostVarWorkflowDirectory, $workflowName);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $filePath
     *
     * @return void
     */
    protected function writeWorkflowFileConsoleMessage(OutputInterface $output, string $filePath): void
    {
        $output->writeln('');
        $output->writeln('To open the workflow graph click the link below (with pressed Ctrl):');
        $output->writeln('');
        $output->writeln(sprintf('  <info>file://%s</info>', $filePath));
        $output->writeln('');
    }
}
