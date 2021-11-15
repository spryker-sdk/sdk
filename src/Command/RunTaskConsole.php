<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * @var string[]
     */
    protected $placeholders = [];

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::COMMAND_DESCRIPTION)
            ->setHelp($this->getHelpText())
            ->addArgument(static::ARGUMENT_TASK, InputArgument::REQUIRED, 'Task of the SDK which should be run.');

        $this->placeholders = $this->getFacade()->dumpUniqueTaskPlaceholderNames();

        foreach ($this->placeholders as $placeholder) {
            $this->addOption(
                $placeholder,
                null,
                InputOption::VALUE_REQUIRED,
            );
        }
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
        $placeholders = array_intersect_key($input->getOptions(), array_flip($this->placeholders));

        $output->write('<info>Running task: </info>' . $taskName, true);

        $this->getFacade()->executeTask($taskName, $placeholders, $this->getFactory()->createStyle($input, $output));

        return static::SUCCESS;
    }

    /**
     * @return string
     */
    protected function getHelpText(): string
    {
        return 'run `<info>{TASK ID}</info>` `<info>--tags=tag1,tag2...</info>` to filter subtasks';
    }
}
