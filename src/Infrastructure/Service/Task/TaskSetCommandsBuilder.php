<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Task;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\ValueObject\ConfigurableCommand;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;

class TaskSetCommandsBuilder
{
    /**
     * @param array<string, array<\SprykerSdk\SdkContracts\Entity\CommandInterface>> $subTasksCommands
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap $taskSetOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function buildTaskSetCommands(array $subTasksCommands, TaskSetOverrideMap $taskSetOverrideMap): array
    {
        $taskSetCommands = [];

        foreach ($subTasksCommands as $taskId => $commands) {
            $taskOverridePlaceholders = $taskSetOverrideMap->getTaskOverridePlaceholders($taskId);
            $taskTags = $taskSetOverrideMap->getTaskTags($taskId);
            $taskStopOnError = $taskSetOverrideMap->getTaskStopOnError($taskId);

            foreach ($commands as $command) {
                $taskSetCommands[] = $this->createNewTaskSetCommand(
                    $command,
                    $taskOverridePlaceholders,
                    $taskTags,
                    $taskStopOnError,
                );
            }
        }

        return $taskSetCommands;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param array<string, mixed>|null $taskOverridePlaceholders
     * @param array<string>|null $taskTags
     * @param bool|null $taskStopOnError
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createNewTaskSetCommand(
        CommandInterface $command,
        ?array $taskOverridePlaceholders,
        ?array $taskTags,
        ?bool $taskStopOnError
    ): CommandInterface {
        if ($command instanceof ExecutableCommandInterface || $command->getType() === 'php') {
            return new ConfigurableCommand($command, $taskStopOnError, $taskTags);
        }

        return new Command(
            $this->getCommandString($command, $taskOverridePlaceholders),
            $command->getType(),
            $taskStopOnError ?? $command->hasStopOnError(),
            $taskTags ?? $command->getTags(),
            $command->getConverter(),
            $command->getStage(),
            $command instanceof ErrorCommandInterface ? $command->getErrorMessage() : '',
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     * @param array<string, mixed>|null $taskOverridePlaceholders
     *
     * @return string
     */
    protected function getCommandString(CommandInterface $command, ?array $taskOverridePlaceholders): string
    {
        if ($taskOverridePlaceholders === null) {
            return $command->getCommand();
        }

        $replacementPairs = [];

        foreach ($taskOverridePlaceholders as $placeholder => $newDefinition) {
            if (!isset($newDefinition['name'])) {
                continue;
            }

            $replacementPairs[$placeholder] = $newDefinition['name'];
        }

        return strtr($command->getCommand(), $replacementPairs);
    }
}
