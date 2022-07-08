<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Command;

class CommandLoader implements CommandLoaderInterface
{
    /**
     * @var iterable<\Symfony\Component\Console\Command\Command>
     */
    protected iterable $commands;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader
     */
    protected TaskRunFactoryLoader $commandContainer;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param iterable<\Symfony\Component\Console\Command\Command> $commands
     */
    public function __construct(iterable $commands, TaskRunFactoryLoader $commandContainer, TaskRepositoryInterface $taskRepository)
    {
        $this->commands = $commands;
        $this->commandContainer = $commandContainer;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface>
     */
    public function load(): array
    {
        $commands = [];

        foreach ($this->taskRepository->findAll() as $task) {
            $command = $this->commandContainer->get($task->getId());

            $commands[] = new Command(
                (string)$command->getName(),
                [],
                [],
                $command->getHelp(),
            );
        }

        foreach ($this->commands as $command) {
            if ($command->isHidden()) {
                continue;
            }
            $commands[] = new Command(
                (string)$command->getName(),
                [],
                [],
                $command->getHelp(),
            );
        }

        return $commands;
    }
}
