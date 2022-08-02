<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Reflection;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;

class DtoClass
{
    /**
     * @var \ReflectionClass
     */
    protected ReflectionClass $reflectionClass;

    /**
     * @var array<string, \SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Reflection\DtoProperty>
     */
    protected array $properties = [];

    /**
     * @var array<string, string>
     */
    protected array $propertyMap = [];

    /**
     * @param class-string<\SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Dto> $class
     */
    public function __construct($class)
    {
        $this->reflectionClass = new ReflectionClass($class);
    }

    /**
     * @return object
     */
    public function createInstance()
    {
        return $this->reflectionClass->newInstanceWithoutConstructor();
    }

    /**
     * @return array<string, \SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Reflection\DtoProperty>
     */
    public function getProperties(): array
    {
        if (!$this->properties) {
            $writeableProperties = array_filter(
                $this->reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED),
                fn (ReflectionProperty $property) => !$property->isStatic(),
            );

            $this->properties = [];
            foreach ($writeableProperties as $property) {
                $this->properties[$property->getName()] = new DtoProperty($this->reflectionClass, $property);
            }
        }

        return $this->properties;
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Reflection\DtoProperty
     */
    public function getProperty(string $name): DtoProperty
    {
        $nameNormalized = $this->getPropertyNameNormalized($name);

        if (!isset($this->getProperties()[$nameNormalized])) {
            throw new InvalidArgumentException(sprintf(
                'Missing field `%s` in `%s`',
                $name,
                $this->reflectionClass->getName(),
            ));
        }

        return $this->getProperties()[$nameNormalized];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getPropertyNameNormalized(string $name): string
    {
        if (!$this->propertyMap) {
            $this->propertyMap = [];

            foreach ($this->getProperties() as $propertyName => $property) {
                $this->propertyMap[$propertyName]
                    = $this->propertyMap[$property->getName(DtoProperty::TYPE_UNDERSCORED)]
                    = $this->propertyMap[$property->getName(DtoProperty::TYPE_DASHED)]
                    = $propertyName;
            }
        }

        return $this->propertyMap[$name] ?? $name;
    }
}
