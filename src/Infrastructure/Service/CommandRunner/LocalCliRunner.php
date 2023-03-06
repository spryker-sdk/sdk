<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\CommandRunner;

use SprykerSdk\Sdk\Core\Application\Dependency\CommandRunnerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;
use SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem;
use SprykerSdk\Sdk\Infrastructure\Injector\HelperSetInjectorInterface;
use SprykerSdk\Sdk\Infrastructure\Injector\OutputInjectorInterface;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Enum\Task as EnumTask;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LocalCliRunner implements HelperSetInjectorInterface, CommandRunnerInterface, OutputInjectorInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \Symfony\Component\Console\Helper\ProcessHelper
     */
    protected ProcessHelper $processHelper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param \Symfony\Component\Console\Helper\ProcessHelper $processHelper
     * @param \SprykerSdk\Sdk\Infrastructure\Filesystem\Filesystem $filesystem
     */
    public function __construct(ProcessHelper $processHelper, Filesystem $filesystem)
    {
        $this->processHelper = $processHelper;
        $this->filesystem = $filesystem;
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
        return in_array($command->getType(), [EnumTask::TYPE_LOCAL_CLI, EnumTask::TYPE_LOCAL_CLI_INTERACTIVE], true);
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

        $process = Process::fromShellCommandline($assembledCommand, $this->filesystem->getcwd());
        $process->setTimeout(null);
        $process->setIdleTimeout(null);

        if ($command->getType() === EnumTask::TYPE_LOCAL_CLI_INTERACTIVE) {
            $process->setTty(true);
        }

        $process = $this->processHelper->run(
            $this->output,
            [$process],
        );

        if (
            !$process->isSuccessful() &&
            $command instanceof ErrorCommandInterface &&
            strlen($command->getErrorMessage())
        ) {
            $context->addMessage(
                $command->getCommand(),
                new Message(trim($command->getErrorMessage()), MessageInterface::ERROR),
            );
        }

        $context->setExitCode($process->isSuccessful() ? ContextInterface::SUCCESS_EXIT_CODE : ContextInterface::FAILURE_EXIT_CODE);
        $verbosity = $process->isSuccessful() ? MessageInterface::INFO : MessageInterface::ERROR;

        if ($process->getOutput()) {
            $context->addMessage($command->getCommand(), new Message(trim($process->getOutput()), $verbosity));
        }

        if ($process->getErrorOutput()) {
            $context->addMessage($command->getCommand(), new Message(trim($process->getErrorOutput()), $verbosity));
        }

        return $context;
    }
}
