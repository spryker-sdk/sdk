<?php

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Task;

class TaskMapper implements TaskMapperInterface
{
    public function __construct(
        protected CommandMapperInterface $commandMapper,
        protected PlaceholderMapperInterface $placeholderMapper
    ) {
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Task
     */
    public function mapToInfrastructureEntity(TaskInterface $task): Task
    {
        $entity = new Task(
            $task->getId(),
            $task->getShortDescription(),
        );

        $entity->setHelp($task->getHelp());

        $entity = $this->mapPlaceholders($task->getPlaceholders(), $entity);
        $entity = $this->mapCommands($task->getCommands(), $entity);

        return $entity;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface[] $commands
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
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\PlaceholderInterface[] $placeholders
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
