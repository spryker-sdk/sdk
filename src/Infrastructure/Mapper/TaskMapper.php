<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;
use SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskMapper implements TaskMapperInterface
{
    protected CommandMapperInterface $commandMapper;

    protected PlaceholderMapperInterface $placeholderMapper;

    protected LifecycleMapperInterface $lifecycleMapper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\CommandMapperInterface $commandMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\PlaceholderMapperInterface $placeholderMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\LifecycleMapperInterface $lifecycleMapper
     */
    public function __construct(
        CommandMapperInterface $commandMapper,
        PlaceholderMapperInterface $placeholderMapper,
        LifecycleMapperInterface $lifecycleMapper
    ) {
        $this->commandMapper = $commandMapper;
        $this->placeholderMapper = $placeholderMapper;
        $this->lifecycleMapper = $lifecycleMapper;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function mapToInfrastructureEntity(TaskInterface $task): Task
    {
        $entity = new Task(
            $task->getId(),
            $task->getShortDescription(),
            $this->mapLifecycle($task),
            $task->getVersion(),
            $task->getHelp(),
            $task->getSuccessor(),
            $task->isDeprecated(),
        );

        $entity = $this->mapPlaceholders($task->getPlaceholders(), $entity);
        $entity = $this->mapCommands($task->getCommands(), $entity);

        return $entity;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $taskToUpdate
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function updateInfrastructureEntity(TaskInterface $task, Task $taskToUpdate): Task
    {
        $taskToUpdate
            ->setSuccessor($task->getSuccessor())
            ->setIsDeprecated($task->isDeprecated())
            ->setVersion($task->getVersion())
            ->setHelp($task->getHelp())
            ->setShortDescription($task->getShortDescription());

        $taskToUpdate->setCommands(new ArrayCollection());
        $taskToUpdate->setPlaceholders(new ArrayCollection());

        $taskToUpdate = $this->mapPlaceholders($task->getPlaceholders(), $taskToUpdate);
        $taskToUpdate = $this->mapCommands($task->getCommands(), $taskToUpdate);

        $taskToUpdate->setLifecycle($this->mapLifecycle($task));

        return $taskToUpdate;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    protected function mapCommands(array $commands, Task $task): Task
    {
        foreach ($commands as $command) {
            $commandEntity = $this->commandMapper->mapCommand($command);

            $task->addCommand($commandEntity);
        }

        return $task;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    protected function mapPlaceholders(array $placeholders, Task $task): Task
    {
        foreach ($placeholders as $placeholder) {
            $placeholderEntity = $this->placeholderMapper->mapPlaceholder($placeholder);

            $task->addPlaceholder($placeholderEntity);
        }

        return $task;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface
     */
    protected function mapLifecycle(TaskInterface $task): PersistentLifecycleInterface
    {
        $taskLifecycle = $task->getLifecycle();

        return $taskLifecycle instanceof TaskLifecycleInterface ?
            $this->lifecycleMapper->mapLifecycle($taskLifecycle) :
            new Lifecycle(new RemovedEvent());
    }
}
