<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskSetToTaskConverter
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function convert(TaskSetInterface $taskSet): TaskInterface
    {
        $commands = [];
        $placeholders = [];

        foreach ($taskSet->getSubTasks() as $task) {
            $commands[] = $this->getSubTaskCommands($task, $taskSet);
            $placeholders[] = $task->getPlaceholders();
        }

        $commands = array_merge($taskSet->getCommands(), ...$commands);
        $placeholders = $this->getUniquePlaceholders(array_merge($taskSet->getPlaceholders(), ...$placeholders));

        return new Task(
            $taskSet->getId(),
            $taskSet->getShortDescription(),
            $commands,
            $taskSet->getLifecycle(),
            $taskSet->getVersion(),
            $placeholders,
            $taskSet->getHelp(),
            $taskSet->getSuccessor(),
            $taskSet->isDeprecated(),
            ContextInterface::DEFAULT_STAGE,
            $taskSet->isOptional(),
            $taskSet->getStages(),
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function getSubTaskCommands(TaskInterface $task, TaskSetInterface $taskSet): array
    {
        $stopOnError = $taskSet->getSubTasksStopOnErrorMap()[$task->getId()] ?? null;
        $tags = $taskSet->getSubTasksTagsMap()[$task->getId()] ?? null;

        return array_map(static function (CommandInterface $command) use ($stopOnError, $tags) {
            return new Command(
                $command->getCommand(),
                $command->getType(),
                $stopOnError ?? $command->hasStopOnError(),
                $tags ?? $command->getTags(),
                $command->getConverter(),
                $command->getStage(),
                $command instanceof ErrorCommandInterface ? $command->getErrorMessage() : '',
            );
        }, $task->getCommands());
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function getUniquePlaceholders(array $placeholders): array
    {
        $uniquePlaceholders = [];

        foreach ($placeholders as $placeholder) {
            $uniquePlaceholders[$placeholder->getName()] = $placeholder;
        }

        return array_values($uniquePlaceholders);
    }
}
