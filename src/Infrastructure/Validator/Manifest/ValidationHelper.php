<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskYamlRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface;
use Traversable;

class ValidationHelper
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
     * @param string $taskId
     *
     * @return bool
     */
    public function isTaskNameExist(string $taskId): bool
    {
        return $this->taskYamlRepository->isTaskNameExist($taskId);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function validateName(string $name): bool
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
    public function validatePlaceholderInString(string $string, array $placeholderNames): bool
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
    public function validateConverter(string $name): bool
    {
        return $this->converterRegistry->has($name);
    }
}
