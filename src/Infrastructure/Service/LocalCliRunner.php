<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LocalCliRunner implements CommandRunnerInterface
{
    protected OutputInterface $output;

    protected ProcessHelper $processHelper;

    /**
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     */
    public function __construct(ProcessHelper $processHelper)
    {
        $this->processHelper = $processHelper;
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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Context $context
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Context
     */
    public function execute(CommandInterface $command, Context $context): Context
    {
        $placeholders = array_map(function (mixed $placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($context->getResolvedValues()));

        $values = array_map(function (mixed $value): string {
            return match (gettype($value)) {
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

        $context->setResult($process->getExitCode() ?? Context::SUCCESS_STATUS_CODE);

        foreach (explode(PHP_EOL, $process->getOutput()) as $outputLine) {
            $context->addMessage(new Message($outputLine));
        }

        foreach (explode(PHP_EOL, $process->getErrorOutput()) as $errorLine) {
            $context->addMessage(new Message($errorLine, Message::ERROR));
        }

        return $context;
    }
}
