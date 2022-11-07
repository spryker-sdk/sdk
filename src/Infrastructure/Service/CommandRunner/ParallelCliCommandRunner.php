<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CommandRunner;

use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Command\CliCommandRunnerInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
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
        $placeholders = array_map(function ($placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($context->getResolvedValues()));

        $values = array_map(function ($value): string {
            return is_array($value) ? implode(',', $value) : (string)$value;
        }, array_values($context->getResolvedValues()));

        $pathIndex = array_search('/%path%/', $placeholders);
        $paths = [
            '/Pyz/Glue',
            '/Pyz/Client',
            '/Pyz/Shared',
            '/Pyz/Service',
        ];

        $modules = scandir('src/Pyz/Zed');
        if (!$modules) {
            $modules = [];
        }
        foreach ($modules as $moduleDir) {
            if (in_array($moduleDir, ['.', '..'])) {
                continue;
            }
            $paths[] = '/Pyz/Zed/' . $moduleDir;
        }
        $processes = [];

        foreach ($paths as $path) {
            $values[$pathIndex] = 'src' . $path;
            $assembledCommand = preg_replace($placeholders, $values, $command->getCommand());
            if (!is_string($assembledCommand)) {
                throw new CommandRunnerException(sprintf(
                    'Could not assemble command %s with keys %s',
                    $command->getCommand(),
                    implode(', ', array_keys($values)),
                ));
            }
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
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    protected function runParallel(array $processes, ContextInterface $context, CommandInterface $command): ContextInterface
    {
        foreach ($processes as $process) {
            $process->start();
        }

        do {
            usleep(1000);
            foreach ($processes as $index => $process) {
                if ($process->isRunning()) {
                    continue;
                }
                unset($processes[$index]);
            }
        } while (count($processes) > 0);

        return $context;
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
