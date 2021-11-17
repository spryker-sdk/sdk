<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskHelpCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'task:help';

    /**
     * @var string
     */
    protected const TASK_ID = 'task-id';

    public function __construct(
        protected TaskRepositoryInterface $taskRepository,
        protected PlaceholderResolver $placeholderResolver
    ) {
        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->addArgument(static::TASK_ID, InputArgument::REQUIRED);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $taskId = $input->getArgument(static::TASK_ID);
        $task = $this->taskRepository->findById($taskId);

        if (!$task) {
            $output->writeln(sprintf('<error>No task %s exists</error>', $taskId));

            return static::FAILURE;
        }

        $shortArguments = [];
        $extendedArguments = [];

        foreach ($task->placeholders as $placeholder) {
            $valueResolver = $this->placeholderResolver->getValueResolver($placeholder);
            $placeholderValueResolver[$placeholder->name] = $valueResolver;
            $shortArgument = sprintf('--%s=<%s>', $valueResolver->getAlias(), $valueResolver->getType());

            if ($placeholder->isOptional) {
                $shortArgument = '[' . $shortArgument . ']';
            }
            $shortArguments[] = $shortArgument;
            $extendedArgument = $shortArgument;

            if ($placeholder->isOptional) {
                $extendedArgument = $extendedArgument . ' [optional]';
            }

            $extendedArguments[] = $extendedArgument . ': ' . $valueResolver->getDescription();
        }

        $output->write(sprintf('usage: spryker-sdk task:run %s', $task->id));
        array_map(function (string $shortArgument) use($output): void {
            $output->write(' ' . $shortArgument);
        }, $shortArguments);
        $output->writeln(PHP_EOL);
        array_map(function ($extendedArgument) use ($output): void {
            $output->writeln($extendedArgument);
        }, $extendedArguments);

        return static::SUCCESS;
    }
}