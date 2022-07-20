<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryReadWriteInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\Task>
 */
class TaskRepository extends ServiceEntityRepository implements TaskRepositoryReadWriteInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface
     */
    protected TaskMapperInterface $taskMapper;

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $existingTasks = [];

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface $taskMapper
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        TaskMapperInterface $taskMapper,
        ManagerRegistry $registry,
        iterable $existingTasks = []
    ) {
        parent::__construct($registry, Task::class);
        $this->taskMapper = $taskMapper;
        foreach ($existingTasks as $existingTask) {
            $this->existingTasks[$existingTask->getId()] = $existingTask;
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function create(TaskInterface $task): TaskInterface
    {
        $entity = $this->taskMapper->mapToInfrastructureEntity($task);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $taskToUpdate
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function update(TaskInterface $task, TaskInterface $taskToUpdate): TaskInterface
    {
        if (!$taskToUpdate instanceof Task) {
            throw new InvalidTypeException(sprintf('$taskToUpdate must be instance of %s', Task::class));
        }

        return $this->getEntityManager()->wrapInTransaction(function () use ($task, $taskToUpdate): Task {
            $this->getEntityManager()->remove($taskToUpdate->getLifecycle());

            $entity = $this->taskMapper->updateInfrastructureEntity($task, $taskToUpdate);

            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();

            return $entity;
        });
    }

    /**
     * @param bool $realCommand
     *
     * @return array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function findAllIndexedCollection(bool $realCommand = true): array
    {
        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Task> $tasks */
        $tasks = $this->findAll();

        $tasksMap = [];

        foreach ($tasks as $task) {
            $tasksMap[$task->getId()] = $this->changePhpCommand($task, $realCommand);
        }

        return $tasksMap;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function remove(TaskInterface $task): void
    {
        if (!$task instanceof Task) {
            return;
        }

        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function findById(string $taskId): ?TaskInterface
    {
        $criteria = [
            'id' => $taskId,
        ];

        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Task|null $task */
        $task = $this->findOneBy($criteria);

        return $task === null ? null : $this->changePhpCommand($task);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $task
     * @param bool $realCommand
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function changePhpCommand(TaskInterface $task, bool $realCommand = true): TaskInterface
    {
        $existingCommands = [];
        foreach ($this->existingTasks as $existingTask) {
            foreach ($existingTask->getCommands() as $existingCommand) {
                $existingCommands[get_class($existingCommand)] = $existingCommand;
            }
        }

        $commands = [];
        foreach ($task->getCommands() as $command) {
            if ($realCommand && $command->getType() === 'php' && isset($existingCommands[$command->getCommand()])) {
                $commands[] = $existingCommands[$command->getCommand()];

                continue;
            }
            $commands[] = $command;
        }

        $task->setCommands(new ArrayCollection($commands));

        return $task;
    }
}
