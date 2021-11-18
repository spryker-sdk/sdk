<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner;
use SprykerSdk\Sdk\Presentation\Console\Input\TaskInputDefinition;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TaskRunCommand extends Command
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
     * @param \SprykerSdk\Sdk\Infrastructure\Service\LocalCliRunner $localCliRunner
     */
    public function __construct(
        protected TaskExecutor $taskExecutor,
        protected LocalCliRunner $localCliRunner,
        protected CliValueReceiver $cliValueReceiver,
        protected TaskInputDefinition $taskInputDefinition
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

//        $this->addOption('somebody', null, InputOption::VALUE_OPTIONAL, '', 'Somebody');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        //@todo use service decoration to set input/output
        $this->localCliRunner->setOutput($output);
        $this->localCliRunner->setHelperSet($this->getApplication()->getHelperSet());
        $this->cliValueReceiver->setOutput($output);
        $this->cliValueReceiver->setInput($input);

        $taskId = $input->getArgument(static::TASK_ID);
        $this->setDefinition(
            $this->taskInputDefinition
                ->setBaseDefinition($this->getDefinition())
                ->setTaskId($taskId)
        );

        return $this->taskExecutor->execute($taskId);
    }
}