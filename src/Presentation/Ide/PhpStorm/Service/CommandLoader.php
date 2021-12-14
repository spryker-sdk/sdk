<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\CommandMapperInterface;

class CommandLoader implements CommandLoaderInterface
{
    protected TaskRunFactoryLoader $commandContainer;

    protected CommandMapperInterface $commandMapper;

    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param \SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader $commandContainer
     * @param \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\CommandMapperInterface $commandMapper
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRunFactoryLoader $commandContainer, CommandMapperInterface $commandMapper, TaskRepositoryInterface $taskRepository)
    {
        $this->commandContainer = $commandContainer;
        $this->commandMapper = $commandMapper;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface>
     */
    public function load(): array
    {
        $tasks = $this->taskRepository->findAll();

        return $this->mapTasks($tasks);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface>
     */
    protected function mapTasks(array $tasks): array
    {
        $commands = [];

        foreach ($tasks as $task) {
            $command = $this->commandContainer->get($task->getId());

            $commands[] = $this->commandMapper->mapToIdeCommand($command);
        }

        return $commands;
    }
}
