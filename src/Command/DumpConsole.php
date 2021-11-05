<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Command;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpConsole extends AbstractSdkConsole
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'dump';

    /**
     * @var string
     */
    public const COMMAND_DESCRIPTION = 'Dump tasks list or specific task definition.';

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

        if ($taskName) {
            $taskDefinition = $this->getFacade()->getTaskDefinition($taskName);

            return static::SUCCESS;
        }
        $taskDefinitions = $this->getFacade()->getTaskDefinitions();

        $this->renderTaskDefinitions($output, $taskDefinitions);

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $taskDefinitions
     *
     * @return void
     */
    protected function renderTaskDefinitions(OutputInterface $output, array $taskDefinitions): void
    {
        $headers = ['ID', 'DESCRIPTION'];
        $rows = [];

        foreach ($taskDefinitions as $taskDefinition) {
            $rows[] = [$taskDefinition['id'], $taskDefinition['short_description']];
        }

        $output->writeln('List of Tasks definitions:');
        $this->printTable($output, $headers, $rows);
    }
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $headers
     * @param array $rows
     *
     * @return void
     */
    protected function printTable(OutputInterface $output, array $headers, array $rows): void
    {
        (new Table($output))
            ->setHeaders($headers)
            ->setRows($rows)
            ->render();
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $title
     * @param string $style
     *
     * @return void
     */
    protected function printTitleBlock(OutputInterface $output, string $title, string $style = 'info'): void
    {
        $output->writeln(
            (new FormatterHelper())
                ->formatBlock($title, $style)
        );
    }

    /**
     * @return string
     */
    protected function getHelpText(): string
    {
        return 'Use <info><TASK ID></info> as parameter` to show specific task definition.';
    }
}
