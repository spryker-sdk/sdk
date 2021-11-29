<?php

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;

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
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function mapToInfrastructureEntity(TaskInterface $task): Task
    {
        $entity = new Task(
            $task->getId(),
            $task->getShortDescription(),
            $task->getVersion(),
            $task->getHelp(),
            $task->getSuccessor(),
            $task->isDeprecated()
        );

        $entity = $this->mapPlaceholders($task->getPlaceholders(), $entity);
        $entity = $this->mapCommands($task->getCommands(), $entity);

        $entity->setLifecycle(
            $this->lifecycleMapper->mapLifecycle($task->getLifecycle())
        );

        return $entity;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $taskToUpdate
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function updateInfrastructureEntity(TaskInterface $task, Task $taskToUpdate): Task
    {
        $taskToUpdate
            ->setSuccessor($task->getSuccessor())
            ->setDeprecated($task->isDeprecated())
            ->setVersion($task->getVersion())
            ->setHelp($task->getHelp())
            ->setShortDescription($task->getShortDescription());

        $taskToUpdate->setCommands(new ArrayCollection());
        $taskToUpdate->setPlaceholders(new ArrayCollection());

        $taskToUpdate = $this->mapPlaceholders($task->getPlaceholders(), $taskToUpdate);
        $taskToUpdate = $this->mapCommands($task->getCommands(), $taskToUpdate);

        $taskToUpdate->setLifecycle(
            $this->lifecycleMapper->mapLifecycle($task->getLifecycle())
        );

        return $taskToUpdate;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[] $commands
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[] $placeholders
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\Task $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task>
     */
    protected function mapPlaceholders(array $placeholders, Task $task): Task
    {
        foreach ($placeholders as $placeholder) {
            $placeholderEntity = $this->placeholderMapper->mapPlaceholder($placeholder);

            $task->addPlaceholder($placeholderEntity);
        }

        return $task;
    }
}
