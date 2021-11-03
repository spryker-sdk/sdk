<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\SprykerSdk\Console;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SdkConsole extends AbstractSdkConsole
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'sdk';
    /**
     * @var string
     */
    protected const COMMAND_DESCRIPTION = 'Runs a SDK process.';
    /**
     * @var string
     */
    public const ARGUMENT_TASK = 'task';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp($this->getHelpText())
            ->addArgument(static::ARGUMENT_TASK, InputArgument::REQUIRED, 'Task of the SDK which should be run.');
        // Add options for arguments
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $taskName = $this->getTaskName($input);

        return static::CODE_SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    protected function getTaskName(InputInterface $input): string
    {
        $name = current((array)$input->getArgument(static::ARGUMENT_TASK));
        if ($name === false) {
            throw new RuntimeException('Cannot retrieve Task name');
        }

        return $name;
    }

    /**
     * @return string
     */
    protected function getHelpText(): string
    {
        return 'Use `console sdk <info>{TASK ID}</info>` to to run specific task.';
    }
}
