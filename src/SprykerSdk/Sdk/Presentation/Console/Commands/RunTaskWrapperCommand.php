<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunTaskWrapperCommand extends Command
{
    protected TaskExecutor $taskExecutor;

    protected array $taskOptions;

    protected string $description;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor $taskExecutor
     * @param array<\Symfony\Component\Console\Input\InputOption> $taskOptions
     * @param string $description
     * @param string $name
     */
    public function __construct(
        TaskExecutor $taskExecutor,
        array $taskOptions,
        string $description,
        string $name
    ) {
        $this->description = $description;
        $this->taskOptions = $taskOptions;
        $this->taskExecutor = $taskExecutor;
        parent::__construct($name);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->setDescription($this->description);

        foreach ($this->taskOptions as $taskOption) {
            $this->addOption(
                $taskOption->getName(),
                null,
                $taskOption->isValueOptional() ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED,
                $taskOption->getDescription(),
                $taskOption->getDefault(),
            );
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        return $this->taskExecutor->execute($this->getName());
    }
}
