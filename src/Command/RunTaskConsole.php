<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunTaskConsole extends AbstractSdkConsole
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'run';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Runs task(s) process.';

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
            ->addArgument(static::ARGUMENT_TASK, InputArgument::OPTIONAL, 'Task of the SDK which should be run.');
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
        $taskName = current((array)$input->getArgument(static::ARGUMENT_TASK));

        $output->write('<info>Running task: </info>' . $taskName, true);

        echo $taskName;

        return static::SUCCESS;
    }

    /**
     * @return string
     */
    protected function getHelpText(): string
    {
        return 'At least one of these values `<info>{TASK ID}</info>` or `<info>--tags</info>` option must be present.';
    }
}
