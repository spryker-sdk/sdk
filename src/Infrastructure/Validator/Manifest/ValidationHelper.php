<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Validator\Manifest;

use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
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
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface $valueResolverRegistry
     * @param iterable<string, \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\QuestionFactoryInterface> $factoryTypes
     */
    public function __construct(
        ValueResolverRegistryInterface $valueResolverRegistry,
        ConverterRegistryInterface $converterRegistry,
        iterable $factoryTypes
    ) {
        $this->valueResolverRegistry = $valueResolverRegistry;
        $this->converterRegistry = $converterRegistry;
        $this->factoryTypes = $factoryTypes instanceof Traversable ? iterator_to_array($factoryTypes) : $factoryTypes;
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
     * @param string $task
     * @param array<string> $placeholderNames
     *
     * @return bool
     */
    public function validatePlaceholderInCommand(string $task, array $placeholderNames): bool
    {
        foreach ($placeholderNames as $name) {
            if (strpos($task, $name) === false) {
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
