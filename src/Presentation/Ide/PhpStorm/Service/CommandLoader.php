<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Command;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param iterable<\Symfony\Component\Console\Command\Command> $commands
     * @param \SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader $commandContainer
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
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

            $commands[] = $this->createCommand($command);
        }

        foreach ($this->commands as $command) {
            if ($command->isHidden()) {
                continue;
            }
            $commands[] = $this->createCommand($command);
        }

        return $commands;
    }

    /**
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\CommandInterface
     */
    protected function createCommand(SymfonyCommand $command): CommandInterface
    {
        return new Command(
            (string)$command->getName(),
            [],
            [],
            $command->getHelp(),
        );
    }
}
