<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class TaskSetPlaceholdersFactory
{
    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, array<string, mixed>> $allTasksConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>>
     */
    public function getSubTasksPlaceholders(
        array $taskSetConfiguration,
        array $allTasksConfigurations,
        array $existingTasks
    ): array {
        $placeholders = [];

        foreach ($taskSetConfiguration['tasks'] as $task) {
            $placeholders[(string)$task['id']] = isset($allTasksConfigurations[$task['id']])
                ? $this->createPlaceholdersFromDefinitions($allTasksConfigurations[$task['id']]['placeholders'])
                : $existingTasks[$task['id']]->getPlaceholders();
        }

        return $placeholders;
    }

    /**
     * @param array<array<string, mixed>> $placeholders
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function createPlaceholdersFromDefinitions(array $placeholders): array
    {
        return array_map(
            fn (array $placeholder): PlaceholderInterface => $this->createPlaceHolderFromArray($placeholder),
            $placeholders,
        );
    }

    /**
     * @param array<string, mixed> $placeholderData
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceHolderFromArray(array $placeholderData): PlaceholderInterface
    {
        if (!isset($placeholderData['name'])) {
            throw new InvalidArgumentException('Placeholder name is not set in %s');
        }

        if (!isset($placeholderData['value_resolver'])) {
            throw new InvalidArgumentException('Placeholder value resolver is not set in %s');
        }

        $configuration = $placeholderData['configuration'] ?? [];

        return isset($placeholderData['optional'])
            ? new Placeholder(
                $placeholderData['name'],
                $placeholderData['value_resolver'],
                $configuration,
                $placeholderData['optional'],
            )
            : new Placeholder($placeholderData['name'], $placeholderData['value_resolver'], $configuration);
    }
}
