<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection;

use InvalidArgumentException;
use LogicException;
use ReflectionNamedType;
use ReflectionProperty;

class DtoProperty
{
    /**
     * @var string
     */
    public const TYPE_DEFAULT = 'default';

    /**
     * @var string
     */
    public const TYPE_UNDERSCORED = 'underscored';

    /**
     * @var string
     */
    public const TYPE_DASHED = 'dashed';

    /**
     * @var \ReflectionProperty
     */
    protected ReflectionProperty $reflectionProperty;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var array<string, string>
     */
    protected array $altNames;

    /**
     * @var bool
     */
    protected bool $isRequired;

    /**
     * @var mixed
     */
    protected mixed $defaultValue;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var bool
     */
    protected bool $isArray;

    /**
     * @param \ReflectionProperty $reflectionProperty
     */
    public function __construct(ReflectionProperty $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty;
        $this->initialize($reflectionProperty);
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return void
     */
    protected function initialize(ReflectionProperty $property): void
    {
        $this->parseType($property);

        $this->name = $property->getName();
        $this->defaultValue = $property->getDefaultValue();
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @throws \LogicException
     *
     * @return void
     */
    protected function parseType(ReflectionProperty $property): void
    {
        $type = $property->getType();
        if (!$type instanceof ReflectionNamedType) {
            throw new LogicException(sprintf(
                'Property `%s::%s` has an unsupported type',
                $property->getDeclaringClass(),
                $property->getName(),
            ));
        }

        $docType = '';
        if (preg_match('/@var\s+(?<type>\S+)/', $property->getDocComment() ?: '', $matches)) {
            $docType = $matches['type'] ?? '';
        }

        $docTypesList = explode('|', $docType);
        $docTypesWithoutNull = array_diff($docTypesList, ['null']);
        $docPropertyType = reset($docTypesWithoutNull) ?: '';
        $docIsArray = mb_substr($docPropertyType, -2) === '[]';
        if ($docIsArray) {
            $docPropertyType = rtrim($docPropertyType, '[]');
        }

        $phpType = $type->getName();
        $this->isArray = $docIsArray || $phpType === 'array';

        if ($this->isArray) {
            $this->type = $docPropertyType ?: 'mixed';
        } else {
            $this->type = $docPropertyType ?: $phpType;
        }

        $this->isRequired = !$type->allowsNull() && $property->getDefaultValue() === null;
    }

    /**
     * @param string|null $type
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getName(?string $type = null): string
    {
        if ($type === null || $type === static::TYPE_DEFAULT) {
            return $this->name;
        }

        if (!isset($this->altNames)) {
            $underscored = strtolower(preg_replace('/(?<!^|[A-Z])([A-Z]+)/u', '_$1', $this->name) ?? '');
            $this->altNames = [
                static::TYPE_UNDERSCORED => $underscored,
                static::TYPE_DASHED => strtr($underscored, ['_' => '-']),
            ];
        }

        return $this->altNames[$type] ?? throw new InvalidArgumentException(sprintf('Invalid type `%s`', $type));
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }
}
