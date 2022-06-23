<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoClass;
use SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoProperty;

class Dto implements FromArrayToArrayInterface
{
    /**
     * @var array<string>
     */
    protected const EXPECTED_TYPES = ['bool', 'int', 'string', 'float', 'mixed'];

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoClass
     */
    protected static function getMetadata(): DtoClass
    {
        static $map = [];

        if (!isset($map[static::class])) {
            $map[static::class] = new DtoClass(static::class);
        }

        return $map[static::class];
    }

    /**
     * @param array|null $data
     * @param bool $ignoreMissing
     *
     * @return static
     */
    public static function create(?array $data = null, bool $ignoreMissing = false)
    {
        /** @var static $instance */
        $instance = static::getMetadata()->createInstance();

        if ($data) {
            $instance->setFromArray($data, $ignoreMissing);
        }

        $instance->fillDefaults();
        $instance->validate();

        return $instance;
    }

    /**
     * @param array $data
     * @param bool $ignoreMissing
     *
     * @return static
     */
    public static function fromArray(array $data, bool $ignoreMissing = false)
    {
        return static::create($data, $ignoreMissing);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function toArray(string $type = DtoProperty::TYPE_DEFAULT): array
    {
        $values = [];
        foreach (static::getMetadata()->getProperties() as $field => $property) {
            $value = $this->$field;

            if ($property->isArray()) {
                $value = $this->toArrayValue($property, $value, $type);
            } else {
                $value = $this->toSingularValue($value, $type);
            }

            $values[$property->getName($type)] = $value;
        }

        return $values;
    }

    /**
     * @param mixed $value
     * @param string $type
     *
     * @return mixed
     */
    protected function toSingularValue($value, string $type = DtoProperty::TYPE_DEFAULT)
    {
        if ($value instanceof Dto) {
            $value = $value->toArray($type);
        } elseif ($value instanceof FromArrayToArrayInterface) {
            $value = $value->toArray();
        }

        return $value;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoProperty $property
     * @param array $arrayValue
     * @param string $type
     *
     * @return array
     */
    protected function toArrayValue(DtoProperty $property, $arrayValue, string $type = DtoProperty::TYPE_DEFAULT): array
    {
        if (!is_iterable($arrayValue) || empty($property->getKeyType())) {
            return [];
        }

        return $this->toNestedArrayValue($property->getKeyType(), $arrayValue, $type);
    }

    /**
     * @param array $keyStack
     * @param array $arrayValue
     * @param string $type
     *
     * @return array
     */
    protected function toNestedArrayValue(array $keyStack, $arrayValue, string $type = DtoProperty::TYPE_DEFAULT): array
    {
        if (!is_iterable($arrayValue)) {
            return [];
        }

        $array = [];
        array_shift($keyStack);
        foreach ($arrayValue as $key => $value) {
            $value = (bool)$keyStack
                ? $this->toNestedArrayValue($keyStack, $value, $type)
                : $this->toSingularValue($value, $type);

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * @param array $data
     * @param bool $ignoreMissing
     *
     * @return $this
     */
    protected function setFromArray(array $data, bool $ignoreMissing)
    {
        foreach ($data as $field => $value) {
            $property = $this->getProperty($field, $ignoreMissing);

            if (!$property) {
                continue;
            }

            if ($property->isArray()) {
                $value = $this->fromCollectionValue($property, $value, $ignoreMissing);
            } else {
                $value = $this->fromSingularValue($property, $value, $ignoreMissing);
            }

            $this->{$property->getName()} = $value;
        }

        return $this;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoProperty $property
     * @param mixed $value
     * @param bool $ignoreMissing
     *
     * @return mixed
     */
    protected function fromSingularValue(DtoProperty $property, $value, bool $ignoreMissing = false)
    {
        if (is_subclass_of($property->getType(), FromArrayToArrayInterface::class)) {
            /** @var \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\FromArrayToArrayInterface $className */
            $className = $property->getType();

            $value = is_array($value) ? $className::fromArray($value, $ignoreMissing) : null;
        }

        $this->checkType($property, $value);

        return $value;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoProperty $property
     * @param array $collectionValue
     * @param bool $ignoreMissing
     *
     * @return array
     */
    protected function fromCollectionValue(DtoProperty $property, $collectionValue, bool $ignoreMissing = false): array
    {
        if (!is_iterable($collectionValue) || empty($property->getKeyType())) {
            return [];
        }

        return $this->fromNestedCollectionValue($property->getKeyType(), $property, $collectionValue, $ignoreMissing);
    }

    /**
     * @param array $keyStack
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoProperty $property
     * @param array $collectionValue
     * @param bool $ignoreMissing
     *
     * @return array
     */
    protected function fromNestedCollectionValue(
        array $keyStack,
        DtoProperty $property,
        $collectionValue,
        bool $ignoreMissing = false
    ): array {
        if (!is_iterable($collectionValue)) {
            return [];
        }

        $collection = [];
        array_shift($keyStack);
        foreach ($collectionValue as $key => $value) {
            $value = (bool)$keyStack
                ? $this->fromNestedCollectionValue($keyStack, $property, $value, $ignoreMissing)
                : $this->fromSingularValue($property, $value, $ignoreMissing);

            $collection[$key] = $value;
        }

        return $collection;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoProperty $property
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function checkType(DtoProperty $property, $value): void
    {
        $expectedType = $this->normalizeType($property->getType());
        $actualType = $this->normalizeType(gettype($value));

        if (
            in_array($expectedType, static::EXPECTED_TYPES, true)
            || in_array($actualType, static::EXPECTED_TYPES, true)
        ) {
            if ($expectedType === 'mixed' || $actualType === $expectedType) {
                return;
            }

            throw new InvalidArgumentException(sprintf(
                'Type of field `%s` is `%s`, expected `%s`',
                $property->getName(),
                $actualType,
                $expectedType,
            ));
        }
    }

    /**
     * @param string $field
     * @param bool $ignoreMissing
     *
     * @throws \InvalidArgumentException
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoProperty|null
     */
    protected function getProperty(string $field, bool $ignoreMissing): ?DtoProperty
    {
        try {
            return static::getMetadata()->getProperty($field);
        } catch (InvalidArgumentException $exception) {
            if (!$ignoreMissing) {
                throw $exception;
            }
        }

        return null;
    }

    /**
     * @return $this
     */
    protected function fillDefaults()
    {
        foreach (static::getMetadata()->getProperties() as $name => $property) {
            if (property_exists($this, $name)) {
                continue;
            }

            if ($property->getDefaultValue() !== null || !$property->isRequired()) {
                $this->$name = $property->getDefaultValue();
            }
        }

        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function validate(): void
    {
        $errors = [];
        foreach (static::getMetadata()->getProperties() as $name => $property) {
            if (!property_exists($this, $name) && $property->isRequired()) {
                $errors[] = $name;
            }
        }

        if ($errors) {
            throw new InvalidArgumentException('Required fields missing: ' . implode(', ', $errors));
        }
    }

    /**
     * @param string $typeName
     *
     * @return string
     */
    protected function normalizeType(string $typeName): string
    {
        $typeName = strtolower($typeName);

        return ['boolean' => 'bool', 'double' => 'float', 'integer' => 'int'][$typeName] ?? $typeName;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'data' => $this->toArray(),
            'extends' => get_parent_class($this),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    public function __unserialize(array $data): void
    {
        $this->setFromArray($data, true);
    }
}
