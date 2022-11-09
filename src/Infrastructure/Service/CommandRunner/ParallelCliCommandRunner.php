<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CommandRunner;

use SprykerSdk\Sdk\Core\Application\Dependency\MultiProcessCommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Command\CliCommandRunnerInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ParallelCliCommandRunner implements CliCommandRunnerInterface
{
    protected HelperSet $helperSet;

    protected InputInterface $input;

    protected OutputInterface $output;

    /**
     * @var \Symfony\Component\Console\Helper\ProcessHelper
     */
    protected ProcessHelper $processHelper;

    /**
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     */
    public function __construct(ProcessHelper $processHelper)
    {
        $this->processHelper = $processHelper;
    }

    /**
     * @inheritDoc
     */
    public function setHelperSet(HelperSet $helperSet): void
    {
        $this->helperSet = $helperSet;
    }

    /**
     * @inheritDoc
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'cli_parallel';
    }

    /**
     * @inheritDoc
     */
    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
    }

    /**
     * @inheritDoc
     */
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * @inheritDoc
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     */
    public function execute(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        if (!$command instanceof MultiProcessCommandInterface) {
            $this->output->writeln('Incompatible task for parallel execution');

            return $context;
        }

        $splitBy = $command->getSplitCallback()();


        $processes = [];
        foreach ($splitBy as $splitItem) {
            $assembledCommand = sprintf('%s %s', $command->getCommand(), $splitItem);
            $process = Process::fromShellCommandline($assembledCommand);
            $process->setTimeout(null);
            $process->setIdleTimeout(null);
            $processes[] = $process;
        }

        return $this->runParallel($processes, $context, $command);
    }

    /**
     * @param array<\Symfony\Component\Process\Process> $processes
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\MultiProcessCommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    protected function runParallel(
        array $processes,
        ContextInterface $context,
        MultiProcessCommandInterface $command
    ): ContextInterface {
        $maxConcurrentWorkers = $command->getProcessesNum();

        $chunks = array_chunk($processes, $maxConcurrentWorkers);
        foreach ($chunks as $chunk) {
            $this->runChunk($chunk);
            foreach ($chunk as $process) {
                $this->updateContext($context, $process, $command);
            }
        }

        return $context;
    }

    /**
     * @param array<\Symfony\Component\Process\Process> $processes
     *
     * @return void
     */
    protected function runChunk(array $processes): void
    {
        foreach ($processes as $process) {
            $process->start();
        }

        do {
            usleep(300);
            foreach ($processes as $index => $process) {
                if (!$process->isRunning()) {
                    unset($processes[$index]);
                }
            }
        } while (count($processes) > 0);
    }

    protected function calculateProcessCount(): int
    {
        $availableCpuCoresNum = $this->getAvailbaleCpuCoresNum();
        if ($availableCpuCoresNum == 1) {
            return $availableCpuCoresNum;
        }

        if ($availableCpuCoresNum > 1) {
            return (int)($availableCpuCoresNum / 2);
        }

        return 1;
    }

    /**
     * @return int
     */
    protected function getAvailbaleCpuCoresNum(): int
    {
        if (is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            preg_match_all('/^processor/m', $cpuinfo, $matches);

            return count($matches[0]);
        }

        return 1;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     * @param \Symfony\Component\Process\Process $process
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\MultiProcessCommandInterface $command
     *
     * @return void
     */
    protected function updateContext(ContextInterface $context, Process $process, MultiProcessCommandInterface $command): void
    {
        if (
            $process->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE &&
            $command instanceof ErrorCommandInterface &&
            strlen($command->getErrorMessage())
        ) {
            $context->addMessage(
                $command->getCommand(),
                new Message(trim($command->getErrorMessage()), MessageInterface::ERROR),
            );
        }

        $context->setExitCode($process->getExitCode() ?? ContextInterface::SUCCESS_EXIT_CODE);
        $verbosity = $process->isSuccessful() ? MessageInterface::INFO : MessageInterface::ERROR;

        if ($process->getOutput()) {
            $context->addMessage($command->getCommand(), new Message(trim($process->getOutput()), $verbosity));
        }

        if ($process->getErrorOutput()) {
            $context->addMessage($command->getCommand(), new Message(trim($process->getErrorOutput()), $verbosity));
        }
    }

    /**
     * Stub for the passed callback function
     *
     * @return callable
     */
    protected function callableStub(): callable
    {
        return function () {
            $targetPath = '/project/src';
            $backOfficePath = '/Pyz/Zed';
            $gluePath = '/Pyz/Glue';
            $clientPath = 'Pyz/Client';
            $sharedPath = 'Pyz/Shared';
            $servicePath = 'Pyz/Service';
        };
    }
}
