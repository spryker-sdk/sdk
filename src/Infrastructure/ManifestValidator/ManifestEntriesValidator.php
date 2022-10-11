<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\ManifestValidator;

use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface;
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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface
     */
    private TaskYamlRepositoryInterface $taskYamlRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface $valueResolverRegistry
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface $converterRegistry
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface $taskYamlRepository
     * @param iterable<string, \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\QuestionFactoryInterface> $factoryTypes
     */
    public function __construct(
        ValueResolverRegistryInterface $valueResolverRegistry,
        ConverterRegistryInterface $converterRegistry,
        TaskYamlRepositoryInterface $taskYamlRepository,
        iterable $factoryTypes
    ) {
        $this->valueResolverRegistry = $valueResolverRegistry;
        $this->converterRegistry = $converterRegistry;
        $this->taskYamlRepository = $taskYamlRepository;
        $this->factoryTypes = $factoryTypes instanceof Traversable ? iterator_to_array($factoryTypes) : $factoryTypes;
    }

    /**
     * @param array<string> $taskIds
     *
     * @return array<string, array<string>>
     */
    public function getTaskPlaceholders(array $taskIds): array
    {
        return $this->taskYamlRepository->getTaskPlaceholders($taskIds);
    }

    /**
     * @param string $taskId
     *
     * @return bool
     */
    public function isTaskNameExist(string $taskId): bool
    {
        return $this->taskYamlRepository->isTaskIdExist($taskId);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isNameValid(string $name): bool
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
     * @param array<string> $placeholderNames
     *
     * @return bool
     */
    public function isPlaceholderInStringValid(string $string, array $placeholderNames): bool
    {
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
    public function isConverterValid(string $name): bool
    {
        return $this->converterRegistry->has($name);
    }
}
