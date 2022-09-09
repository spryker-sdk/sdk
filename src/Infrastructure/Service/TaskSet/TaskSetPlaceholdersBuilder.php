<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class TaskSetPlaceholdersBuilder
{
    /**
     * @param array<string, array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>> $subTasksPlaceholders
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto $placeholdersOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function buildTaskSetPlaceholders(array $subTasksPlaceholders, TaskSetOverrideMapDto $placeholdersOverrideMap): array
    {
        $subTasksPlaceholders = $this->applyOverridePlaceholderMap($subTasksPlaceholders, $placeholdersOverrideMap);
        $subTasksPlaceholders = $this->applySharedPlaceholderMap($subTasksPlaceholders, $placeholdersOverrideMap);

        return $this->makePlaceHoldersUnique($subTasksPlaceholders);
    }

    /**
     * @param array<string, array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>> $placeholders
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto $placeholdersOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function applyOverridePlaceholderMap(array $placeholders, TaskSetOverrideMapDto $placeholdersOverrideMap): array
    {
        foreach ($placeholdersOverrideMap->getOverridePlaceholdersMap() as $taskId => $overrideMap) {
            foreach ($overrideMap as $placeholderName => $newPlaceholderDefinition) {
                foreach ($placeholders[$taskId] as $index => $placeholder) {
                    if ($placeholder->getName() !== $placeholderName) {
                        continue;
                    }
                    $newPlaceholder = $this->createOverrodePlaceholder($placeholder, $newPlaceholderDefinition);
                    $placeholders[$taskId][$index] = $newPlaceholder;
                }
            }
        }

        return array_merge(...array_values($placeholders));
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto $placeholdersOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function applySharedPlaceholderMap(array $placeholders, TaskSetOverrideMapDto $placeholdersOverrideMap): array
    {
        foreach ($placeholdersOverrideMap->getSharedPlaceholdersMap() as $placeholderName => $placeholderConfiguration) {
            $placeholder = $this->getFirstPlaceholderByName($placeholders, $placeholderName);
            $newPlaceholder = $this->overrideSharedPlaceholderConfig($placeholder, $placeholderConfiguration);
            $placeholders = $this->removePlaceholdersByName($placeholders, $placeholderName);
            $placeholders[] = $newPlaceholder;
        }

        return $placeholders;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function getFirstPlaceholderByName(array $placeholders, string $name): PlaceholderInterface
    {
        foreach ($placeholders as $placeholder) {
            if ($placeholder->getName() === $name) {
                return clone $placeholder;
            }
        }

        throw new InvalidArgumentException(sprintf('Placeholder %s is not found', $name));
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $placeholders
     * @param string $name
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function removePlaceholdersByName(array $placeholders, string $name): array
    {
        return array_filter($placeholders, fn (PlaceholderInterface $placeholder) => $placeholder->getName() !== $name);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $targetPlaceholder
     * @param array<string, mixed> $config
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function overrideSharedPlaceholderConfig(
        PlaceholderInterface $targetPlaceholder,
        array $config
    ): PlaceholderInterface {
        $newPlaceholderDefinition = [];

        if (isset($config['description'])) {
            $newPlaceholderDefinition['configuration']['description'] = $config['description'];
        }

        return $this->createOverrodePlaceholder($targetPlaceholder, $newPlaceholderDefinition);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $targetPlaceholder
     * @param array<string, mixed> $newPlaceholderDefinition
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createOverrodePlaceholder(
        PlaceholderInterface $targetPlaceholder,
        array $newPlaceholderDefinition
    ): PlaceholderInterface {
        if (!isset($newPlaceholderDefinition['name'])) {
            $newPlaceholderDefinition['name'] = $targetPlaceholder->getName();
        }

        if (!isset($newPlaceholderDefinition['value_resolver'])) {
            $newPlaceholderDefinition['value_resolver'] = $targetPlaceholder->getValueResolver();
        }

        $newPlaceholderDefinition['configuration'] = isset($newPlaceholderDefinition['configuration'])
            ? array_merge($targetPlaceholder->getConfiguration(), $newPlaceholderDefinition['configuration'])
            : $targetPlaceholder->getConfiguration();

        if (!isset($newPlaceholderDefinition['optional'])) {
            $newPlaceholderDefinition['optional'] = $targetPlaceholder->isOptional();
        }

        return new Placeholder(
            $newPlaceholderDefinition['name'],
            $newPlaceholderDefinition['value_resolver'],
            $newPlaceholderDefinition['configuration'],
            $newPlaceholderDefinition['optional'],
        );
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $subTasksPlaceholders
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function makePlaceHoldersUnique(array $subTasksPlaceholders): array
    {
        $uniquePlaceholders = [];

        foreach ($subTasksPlaceholders as $subTasksPlaceholder) {
            $uniquePlaceholders[$subTasksPlaceholder->getName()] = $subTasksPlaceholder;
        }

        return array_values($uniquePlaceholders);
    }
}
