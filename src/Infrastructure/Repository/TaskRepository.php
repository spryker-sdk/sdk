<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\ValueObject\ConfigurableCommand;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetCommandsBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

/**
 * @extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository<\SprykerSdk\Sdk\Infrastructure\Entity\Task>
 */
class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface
     */
    protected TaskMapperInterface $taskMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetCommandsBuilder
     */
    protected TaskSetCommandsBuilder $taskSetCommandsBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory
     */
    protected TaskSetOverrideMapDtoFactory $taskSetOverrideMapFactory;

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $existingTasks = [];

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\TaskMapperInterface $taskMapper
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetCommandsBuilder $taskSetCommandsBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoFactory $taskSetOverrideMapFactory
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(
        TaskMapperInterface $taskMapper,
        ManagerRegistry $registry,
        TaskSetCommandsBuilder $taskSetCommandsBuilder,
        TaskSetOverrideMapDtoFactory $taskSetOverrideMapFactory,
        iterable $existingTasks = []
    ) {
        parent::__construct($registry, Task::class);
        $this->taskMapper = $taskMapper;
        $this->taskSetCommandsBuilder = $taskSetCommandsBuilder;
        $this->taskSetOverrideMapFactory = $taskSetOverrideMapFactory;
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
     * @param array<string> $taskIds
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function findByIds(array $taskIds): array
    {
        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Task> $tasks */
        $tasks = $this->createQueryBuilder('t', 't.id')
            ->where('t.id IN (:id)')
            ->setParameter('id', $taskIds)
            ->getQuery()
            ->execute();

        $orderedTasks = [];

        foreach ($taskIds as $taskId) {
            if (!isset($tasks[$taskId])) {
                continue;
            }

            $orderedTasks[] = $tasks[$taskId];
        }

        return $orderedTasks;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $task
     * @param bool $realCommand
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function changePhpCommand(TaskInterface $task, bool $realCommand = true): TaskInterface
    {
        if ($realCommand && isset($this->existingTasks[$task->getId()])) {
            $task->setCommands(new ArrayCollection($this->getTaskCommands($this->existingTasks[$task->getId()])));

            return $task;
        }

        $task->setCommands(new ArrayCollection($task->getCommands()));

        return $task;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function getTaskCommands(TaskInterface $task): array
    {
        if ($task instanceof TaskSetInterface) {
            return $this->taskSetCommandsBuilder->buildTaskSetCommands(
                $this->getTaskSetCommands($task),
                $this->taskSetOverrideMapFactory->createFromTaskSet($task),
            );
        }

        return $task->getCommands();
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @throws \InvalidArgumentException
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Entity\CommandInterface>>
     */
    protected function getTaskSetCommands(TaskSetInterface $taskSet): array
    {
        $commands = [];

        foreach ($taskSet->getSubTasks() as $subTask) {
            if (is_string($subTask)) {
                $subTask = $this->findById($subTask);

                if ($subTask === null) {
                    throw new InvalidArgumentException(sprintf('Task %s not found', $subTask));
                }
            }
            if ($subTask instanceof StagedTaskInterface) {
                foreach ($subTask->getCommands() as $command) {
                    $commands[$subTask->getId()][] = new ConfigurableCommand($command, null, null, $subTask->getStage());
                }

                continue;
            }
            $commands[$subTask->getId()] = $subTask->getCommands();
        }

        return $commands;
    }
}
