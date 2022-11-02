<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlPlaceholderReader;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use Traversable;

class ManifestEntriesValidator
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface
     */
    protected ValueResolverRegistryInterface $valueResolverRegistry;

    /**
     * @var array<string, \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\QuestionFactoryInterface>
     */
    protected array $factoryTypes;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface
     */
    protected ConverterRegistryInterface $converterRegistry;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage
     */
    protected TaskStorage $taskStorage;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlPlaceholderReader
     */
    protected TaskYamlPlaceholderReader $placeholderReader;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface $valueResolverRegistry
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface $converterRegistry
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage $taskStorage
     * @param \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlPlaceholderReader $placeholderReader
     * @param iterable<string, \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\QuestionFactoryInterface> $factoryTypes
     */
    public function __construct(
        ValueResolverRegistryInterface $valueResolverRegistry,
        ConverterRegistryInterface $converterRegistry,
        TaskStorage $taskStorage,
        TaskYamlPlaceholderReader $placeholderReader,
        iterable $factoryTypes
    ) {
        $this->valueResolverRegistry = $valueResolverRegistry;
        $this->converterRegistry = $converterRegistry;
        $this->taskStorage = $taskStorage;
        $this->placeholderReader = $placeholderReader;
        $this->factoryTypes = $factoryTypes instanceof Traversable ? iterator_to_array($factoryTypes) : $factoryTypes;
    }

    /**
     * @param array<string> $taskData
     *
     * @return bool
     */
    public function isPlaceholderExists(array $taskData): bool
    {
        [$taskIds, $sharedPlaceholderIds, $placeholderOverrideIds] = $this->extractIdsFromTaskData($taskData);

        $tasksPlaceholders = $this->placeholderReader->getPlaceholdersByIds($taskIds);

        $uniquePlaceholders = [];

        foreach ($tasksPlaceholders as $taskId => $taskPlaceholders) {
            foreach ($taskPlaceholders as $taskPlaceholderName) {
                if (!isset($uniquePlaceholders[$taskPlaceholderName])) {
                    $uniquePlaceholders[$taskPlaceholderName] = true;

                    continue;
                }
                if (
                    in_array($taskPlaceholderName, $sharedPlaceholderIds) ||
                    in_array($taskPlaceholderName, $placeholderOverrideIds[$taskId])
                ) {
                    continue;
                }

                return false;
            }
        }

        return true;
    }

    /**
     * @param array $taskData
     *
     * @return array
     */
    protected function extractIdsFromTaskData(array $taskData): array
    {
        $taskIds = [];
        $placeholderOverrideIds = [];
        $sharedPlaceholderIds = !empty($taskData['shared_placeholders'])
            ? array_keys($taskData['shared_placeholders'])
            : [];

        foreach ($taskData['tasks'] as $subTask) {
            $taskIds[] = $subTask['id'];
            if (!isset($placeholderOverrideIds[$subTask['id']])) {
                $placeholderOverrideIds[$subTask['id']] = [];
            }
            if ($subTask['placeholder_overrides']) {
                $placeholderOverrideIds[$subTask['id']] = array_keys($subTask['placeholder_overrides']);
            }
        }

        return [
            $taskIds,
            $sharedPlaceholderIds,
            $placeholderOverrideIds,
        ];
    }

    /**
     * @param string $taskId
     *
     * @return bool
     */
    public function isTaskIdExist(string $taskId): bool
    {
        return $this->taskStorage->hasManifestWithId($taskId);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isValueResolverNameValid(string $name): bool
    {
        return $this->valueResolverRegistry->has($name);
    }

    /**
     * @return array<string>
     */
    public function getSupportedTypes(): array
    {
        return array_keys($this->factoryTypes);
    }

    /**
     * @param string $string
     * @param array $placeholders
     *
     * @return bool
     */
    public function isCommandStringContainsAllPlaceholders(string $string, array $placeholders): bool
    {
        $placeholderNames = array_map(
            fn (array $placeholder): string => $placeholder['name'],
            $placeholders,
        );

        foreach ($placeholderNames as $name) {
            if (strpos($string, $name) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isConverterExists(string $name): bool
    {
        return $this->converterRegistry->has($name);
    }
}
