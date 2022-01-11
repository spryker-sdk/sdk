<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
use SprykerSdk\SdkContracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
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
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return bool
     */
    public function canHandle(CommandInterface $command): bool
    {
        return $command->getType() === 'local_cli';
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        $placeholders = array_map(function (mixed $placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($context->getResolvedValues()));

        $values = array_map(function (mixed $value): string {
            return match (gettype($value)) {
                'array' => implode(',', $value),
                default => (string)$value,
            };
        }, array_values($context->getResolvedValues()));

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
        );

        $context->setExitCode($process->getExitCode() ?? ContextInterface::SUCCESS_EXIT_CODE);

        foreach (explode(PHP_EOL, $process->getOutput()) as $outputLine) {
            if (!$outputLine) {
                continue;
            }
            $context->addMessage($command->getCommand(), new Message($outputLine, MessageInterface::INFO));
        }

        foreach (explode(PHP_EOL, $process->getErrorOutput()) as $errorLine) {
            if (!$errorLine) {
                continue;
            }
            $context->addMessage(
                $command->getCommand(),
                new Message(
                    $errorLine,
                    !$process->isSuccessful() ? MessageInterface::ERROR : MessageInterface::INFO
                )
            );
        }

        return $context;
    }
}
