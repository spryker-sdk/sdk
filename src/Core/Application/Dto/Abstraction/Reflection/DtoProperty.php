<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Reflection;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class DtoProperty
{
    /**
     * @var string
     */
    protected const DOC_COMMENT_REGEX = '/@var\s+(?<type>.+)/';

    /**
     * @var string
     */
    protected const ARRAY_TYPE_REGEX = '/(?:array(?:<(?:(?<key>[^<>]+)(?:\s*,\s*))?(?<value1>.+)>)?)|(?:(?<value2>\S+)\[\])/';

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
     * @var string
     */
    public const LIST_KEY_TYPE = 'int';

    /**
     * @var \ReflectionClass
     */
    protected ReflectionClass $reflectionClass;

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
    protected array $altNames = [];

    /**
     * @var bool
     */
    protected bool $isRequired;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var bool
     */
    protected bool $isArray;

    /**
     * @var array<string>|null
     */
    protected ?array $keyType = null;

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    public function __construct(ReflectionClass $reflectionClass, ReflectionProperty $reflectionProperty)
    {
        $this->reflectionClass = $reflectionClass;
        $this->reflectionProperty = $reflectionProperty;

        $this->initialize($reflectionClass, $reflectionProperty);
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
    public function getDefaultValue()
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

    /**
     * @return array<string>|null
     */
    public function getKeyType(): ?array
    {
        return $this->keyType;
    }

    /**
     * @param \ReflectionClass $class
     * @param \ReflectionProperty $property
     *
     * @return void
     */
    protected function initialize(ReflectionClass $class, ReflectionProperty $property): void
    {
        $this->name = $property->getName();
        $this->defaultValue = $class->getDefaultProperties()[$property->getName()] ?? null;

        $this->parseType($class, $property);
    }

    /**
     * @param \ReflectionClass $class
     * @param \ReflectionProperty $property
     *
     * @throws \LogicException
     *
     * @return void
     */
    protected function parseType(ReflectionClass $class, ReflectionProperty $property): void
    {
        $type = $property->getType();
        if (!$type instanceof ReflectionNamedType) {
            throw new LogicException(sprintf(
                'Property `%s::%s` has an unsupported type',
                $property->getDeclaringClass()->getName(),
                $property->getName(),
            ));
        }

        $this->type = $type->getName();
        $this->isArray = $type->getName() === ValueTypeEnum::TYPE_ARRAY;
        $this->isRequired = !$type->allowsNull() && !isset($class->getDefaultProperties()[$property->getName()]);

        $this->parseDocType($property);
        $this->resolveType($property);
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return void
     */
    protected function resolveType(ReflectionProperty $property): void
    {
        if ($this->type === 'self') {
            $this->type = $property->getDeclaringClass()->getName();

            return;
        }

        if ($this->type === 'static') {
            $this->type = $this->reflectionClass->getName();
        }
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return void
     */
    protected function parseDocType(ReflectionProperty $property): void
    {
        $docType = '';
        if (preg_match(static::DOC_COMMENT_REGEX, $property->getDocComment() ?: '', $matches)) {
            $docType = $matches['type'] ?? '';
        }

        $docTypesWithoutNull = array_diff(explode('|', $docType), ['null']);
        $docPropertyType = reset($docTypesWithoutNull) ?: '';

        [$type, $keyType] = $this->parseArrayType($docPropertyType);

        $this->keyType = $keyType;
        $this->isArray = $this->isArray || $keyType !== null;

        if ($type) {
            $this->type = $type;
        }
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function parseArrayType(string $type): array
    {
        $keyTypes = null;

        while (preg_match(static::ARRAY_TYPE_REGEX, $type, $matches)) {
            $keyTypes[] = ($matches['key'] ?? '') ?: static::LIST_KEY_TYPE;
            $type = ($matches['value1'] ?? '') ?: ($matches['value2'] ?? '') ?: 'mixed';
        }

        return [$type, $keyTypes];
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

        if (!$this->altNames) {
            $underscored = strtolower(preg_replace('/(?<!^|[A-Z])([A-Z]+)/u', '_$1', $this->name) ?? '');
            $this->altNames = [
                static::TYPE_UNDERSCORED => $underscored,
                static::TYPE_DASHED => strtr($underscored, ['_' => '-']),
            ];
        }

        if (!isset($this->altNames[$type])) {
            throw new InvalidArgumentException(sprintf('Invalid type `%s`', $type));
        }

        return $this->altNames[$type];
    }
}
