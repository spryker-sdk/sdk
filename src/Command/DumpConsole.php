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
            $this->renderTaskDefinition($output, $taskDefinition);

            return static::SUCCESS;
        }
        $taskDefinitions = $this->getFacade()->getTaskDefinitions();

        $this->renderTaskDefinitions($output, $taskDefinitions);

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param array $taskDefinition
     *
     * @return void
     */
    protected function renderTaskDefinition(OutputInterface $output, array $taskDefinition): void
    {
        $taskCommand = $this->getConsoleTaskCommand($taskDefinition);
        $this->printTitleBlock($output, sprintf('usage: %s', $taskCommand));
        $this->printTitleBlock($output, sprintf('stage: %s', $taskDefinition['stage']));
        $output->write('', true);
        $this->printTitleBlock($output, $taskDefinition['short_description'], 'comment');
        $output->write('', true);

        foreach ($taskDefinition['placeholders'] as $placeholder)
        {
            $optional = (empty($placeholder['optional'])) ? '' : '[optional]';
            switch ($placeholder['type']) {
                case 'bool':
                    $output->write(sprintf(
                        '--%s %s',
                        $placeholder['parameterName'],
                        $optional
                    ), 'comment');

                    break;
                default:
                $output->write(sprintf(
                    '--%s=<%s> %s',
                    $placeholder['parameterName'],
                    $placeholder['type'],
                    $optional
                ), 'comment');
            }

            if (!empty($placeholder['description'])) {
                $output->write($placeholder['description'], 'comment');
            }
        }
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

        $this->printTitleBlock($output,'List of Tasks definitions:');
        $this->printTable($output, $headers, $rows);
    }

    /**
     * @param array $taskDefinition
     *
     * @return string
     */
    protected function getConsoleTaskCommand(array $taskDefinition): string
    {
        $taskCommandParameters = [];
        $taskCommandParameters[] = 'spryker-sdk ' . RunTaskConsole::ARGUMENT_TASK;
        $taskCommandParameters[] = $taskDefinition['id'];

        if (!empty($taskDefinition['placeholders'])) {
            foreach ($taskDefinition['placeholders'] as $placeholder)
            {
                $optional = (empty($placeholder['optional'])) ? false : true;

                $taskCommandParameters[] = sprintf(
                    '%s--%s=<%s>%s',
                    $optional ? '[' : '',
                    $placeholder['valueResolver'],
                    $placeholder['type'],
                    $optional ? ']' : '',
                );
            }
        }

        return implode(" ", $taskCommandParameters);
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
