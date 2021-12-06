<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\ErrorCommandInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LocalCliRunner implements CommandRunnerInterface
{
    protected OutputInterface $output;

    protected ProcessHelper $processHelper;

    protected ProgressBar $progressBar;

    /**
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ProgressBar $progressBar
     */
    public function __construct(ProcessHelper $processHelper, ProgressBar $progressBar)
    {
        $this->processHelper = $processHelper;
        $this->progressBar = $progressBar;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param \Symfony\Component\Console\Helper\HelperSet $helperSet
     *
     * @return void
     */
    public function setHelperSet(HelperSet $helperSet)
    {
        $this->processHelper->setHelperSet($helperSet);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'local_cli';
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     * @param array $resolvedValues
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse
     */
    public function execute(CommandInterface $command, array $resolvedValues): CommandResponse
    {
        $placeholders = array_map(function (mixed $placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($resolvedValues));

        $values = array_map(function (mixed $value): string {
            return match (gettype($value)) {
                'array' => implode(',', $value),
                default => (string)$value,
            };
        }, array_values($resolvedValues));

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

        $process = $this->processHelper->run(
            $this->output,
            [$process],
            null,
            function ($type, $buffer) {
                $this->progressBar->setMessage($buffer);
            },
        );

        $commandResponse = new CommandResponse($process->isSuccessful(), (int)$process->getExitCode());

        if (!$process->isSuccessful()) {
            $errorMessage = ($command instanceof ErrorCommandInterface) ? $command->getErrorMessage($commandResponse) : $process->getErrorOutput();
            $commandResponse->setErrorMessage($errorMessage);
        }

        return $commandResponse;
    }
}
