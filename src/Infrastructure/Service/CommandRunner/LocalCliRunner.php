<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CommandRunner;

use SprykerSdk\Sdk\Core\Application\Dependency\CliCommandRunnerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
use SprykerSdk\Sdk\Infrastructure\Service\ProgressBar;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LocalCliRunner implements CliCommandRunnerInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected InputInterface $input;

    /**
     * @var \Symfony\Component\Console\Helper\ProcessHelper
     */
    protected ProcessHelper $processHelper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ProgressBar
     */
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
    public function setOutput(OutputInterface $output): void
    {
        $this->output = $output;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return void
     */
    public function setInput(InputInterface $input): void
    {
        $this->input = $input;
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
        return in_array($command->getType(), ['local_cli', 'local_cli_interactive'], true);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface $context
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    public function execute(CommandInterface $command, ContextInterface $context): ContextInterface
    {
        $placeholders = array_map(function ($placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($context->getResolvedValues()));

        $values = array_map(function ($value): string {
            return is_array($value) ? implode(',', $value) : (string)$value;
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

        if ($command->getType() === 'local_cli_interactive') {
            $process->setTty(true);
        }

        $process = $this->processHelper->run(
            $this->output,
            [$process],
        );

        if (
            $process->getExitCode() !== ContextInterface::SUCCESS_EXIT_CODE &&
            $command instanceof ErrorCommandInterface &&
            strlen($command->getErrorMessage())
        ) {
            $context->addMessage(
                $command->getCommand(),
                new Message($command->getErrorMessage(), MessageInterface::ERROR),
            );
        }

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
                    !$process->isSuccessful() ? MessageInterface::ERROR : MessageInterface::INFO,
                ),
            );
        }

        return $context;
    }
}
