<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class TaskSetCommandsFactory
{
    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, array<string, mixed>> $allTasksConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Entity\CommandInterface>>
     */
    public function getSubTasksCommands(array $taskSetConfiguration, array $allTasksConfigurations, array $existingTasks): array
    {
        $commands = [];

        foreach ($taskSetConfiguration['tasks'] as $task) {
            $commands[(string)$task['id']] = isset($allTasksConfigurations[$task['id']])
                ? [$this->createCommandFromArray($allTasksConfigurations[$task['id']])]
                : $existingTasks[$task['id']]->getCommands();
        }

        return $commands;
    }

    /**
     * @param array<string, mixed> $commandData
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandFromArray(array $commandData): CommandInterface
    {
        $converter = isset($commandData['report_converter']) ? new Converter(
            $commandData['report_converter']['name'],
            $commandData['report_converter']['configuration'],
        ) : null;

        return new Command(
            $commandData['command'],
            $commandData['type'],
            false,
            $commandData['tags'] ?? [],
            $converter,
            $commandData['stage'] ?? ContextInterface::DEFAULT_STAGE,
            $commandData['error_message'] ?? '',
        );
    }
}