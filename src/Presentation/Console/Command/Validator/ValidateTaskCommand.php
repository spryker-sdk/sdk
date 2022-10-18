<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command\Validator;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateTaskCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:validate:task';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface
     */
    public TaskYamlRepositoryInterface $taskYamlRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface $taskYamlRepository
     */
    public function __construct(TaskYamlRepositoryInterface $taskYamlRepository)
    {
        $this->taskYamlRepository = $taskYamlRepository;

        parent::__construct(static::NAME);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->taskYamlRepository->findAll();

        return static::SUCCESS;
    }
}
