<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CommandRunner;

use Closure;
use SprykerSdk\Sdk\Core\Application\Dependency\CommandRunnerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ParallelCliCommandRunner implements CommandRunnerInterface
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
     */
    public function execute(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        $property = 'commandSplitter';
        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\CommandSplitter $splitterEntity */
        $splitterEntity = Closure::bind(
            function () use ($property) {
                return (property_exists($this, $property)) ? $this->$property : null;
            },
            $command,
            $command,
        )();

        if (!$splitterEntity || !$splitterEntity->getClass()) {
            $this->output->writeln('No splitter provided. Should be regular execution executed. So far no action.');

            return $context;
        }

        $className = $splitterEntity->getClass();
        if (!class_exists($className)) {
            $this->output->writeln('Splitter class ' . $className . 'does not exist.');

            return $context;
        }

        /** @var \SprykerSdk\Sdk\Core\Application\Dependency\MultiProcessCommandSplitterInterface $splitter */
        $splitter = new $className();

        $processes = [];
        foreach ($splitter->split() as $splitItem) {
            $processes[] = $this->createProcess(sprintf('%s %s', $command->getCommand(), $splitItem));
        }

        return $this->runParallel($processes, $context, $command, $splitter->getConcurrentProcessNum());
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function createProcess(string $command): Process
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        return $process;
    }

    /**
     * @param array $processes
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param int $concurrentProcessNum
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    protected function runParallel(
        array $processes,
        ContextInterface $context,
        CommandInterface $command,
        int $concurrentProcessNum
    ): ContextInterface {
        $maxConcurrentWorkers = $this->calculateProcessCount($concurrentProcessNum);

        $chunks = array_chunk($processes, $maxConcurrentWorkers);
        foreach ($chunks as $chunk) {
            $this->runChunk($chunk, $context, $command);
        }

        return $context;
    }

    /**
     * @param array<\Symfony\Component\Process\Process> $processes
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return void
     */
    protected function runChunk(
        array $processes,
        ContextInterface $context,
        CommandInterface $command
    ): void {
        foreach ($processes as $process) {
            $process->start();
        }

        do {
            usleep(300);
            foreach ($processes as $index => $process) {
                if (!$process->isRunning()) {
                    $this->updateContext($context, $process, $command);
                    unset($processes[$index]);
                }
            }
        } while (count($processes) > 0);
    }

    /**
     * @param int $providedProcessNum
     *
     * @return positive-int
     */
    protected function calculateProcessCount(int $providedProcessNum = 0): int
    {
        $availableCpuCoresNum = $this->getAvailableCpuCoresNum();
        if ($providedProcessNum > 0 && $providedProcessNum <= $availableCpuCoresNum) {
            return $providedProcessNum;
        }

        $this->output->writeln(
            '<waringn>Process num is too big for the current system. Default will be used instead.</waringn>',
        );

        if ($availableCpuCoresNum > 1) {
            return (int)($availableCpuCoresNum / 2);
        }

        return 1;
    }

    /**
     * @return int
     */
    protected function getAvailableCpuCoresNum(): int
    {
        if (is_file('/proc/cpuinfo')) {
            $cpuinfo = file_get_contents('/proc/cpuinfo');
            if (!$cpuinfo) {
                return 1;
            }

            preg_match_all('/^processor/m', $cpuinfo, $matches);

            return count($matches[0]);
        }

        return 1;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     * @param \Symfony\Component\Process\Process $process
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return void
     */
    protected function updateContext(ContextInterface $context, Process $process, CommandInterface $command): void
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
}
