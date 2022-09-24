<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml;

use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class TaskSetPlaceholdersFactory
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory
     */
    protected PlaceholderFactory $placeholderFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory $placeholderFactory
     */
    public function __construct(PlaceholderFactory $placeholderFactory)
    {
        $this->placeholderFactory = $placeholderFactory;
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, array<string, mixed>> $taskConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>>
     */
    public function getSubTasksPlaceholders(
        array $taskSetConfiguration,
        array $taskConfigurations,
        array $existingTasks
    ): array {
        $placeholders = [];

        foreach ($taskSetConfiguration['tasks'] as $task) {
            $placeholders[(string)$task['id']] = isset($taskConfigurations[$task['id']])
                ? $this->createPlaceholdersFromDefinitions($taskConfigurations[$task['id']]['placeholders'])
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
            fn (array $placeholder): PlaceholderInterface => $this->createPlaceholderFromArray($placeholder),
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
    protected function createPlaceholderFromArray(array $placeholderData): PlaceholderInterface
    {
        if (!isset($placeholderData['name'])) {
            throw new InvalidArgumentException('Placeholder name is not set in %s');
        }

        if (!isset($placeholderData['value_resolver'])) {
            throw new InvalidArgumentException('Placeholder value resolver is not set in %s');
        }

        return $this->placeholderFactory->createFromArray($placeholderData);
    }
}
