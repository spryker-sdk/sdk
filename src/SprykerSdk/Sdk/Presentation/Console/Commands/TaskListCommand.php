<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskListCommand extends Command
{
    protected const NAME = 'task:list';

    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {
        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setAliases(['list:tasks']);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $tasks = $this->taskRepository->findAll();
        //@todo order tasks by stage

        foreach ($tasks as $task) {
            $output->writeln($task->id . ' -- ' . $task->shortDescription);
        }

        return static::SUCCESS;
    }
}