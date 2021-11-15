<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskExecuteCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'task:run';

    /**
     * @var string
     */
    protected const TASK_ID = 'task-id';

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     */
    public function __construct(
        protected TaskExecutor $taskExecutor,
        protected LocalCliRunner $localCliRunner
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
        $this->localCliRunner->setOutput($output);
        $this->localCliRunner->setHelperSet($this->getApplication()->getHelperSet());

        return $this->taskExecutor->execute($input->getArgument(static::TASK_ID));
    }
}