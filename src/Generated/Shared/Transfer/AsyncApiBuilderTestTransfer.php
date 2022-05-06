<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class AsyncApiBuilderTestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const STRING = 'string';

    /**
     * @var string
     */
    public const INTEGER = 'integer';

    /**
     * @var string
     */
    public const DECIMAL = 'decimal';

    /**
     * @var string
     */
    public const ERRORS = 'errors';

    /**
     * @var string|null
     */
    protected $string;

    /**
     * @var int|null
     */
    protected $integer;

    /**
     * @var \Spryker\DecimalObject\Decimal|null
     */
    protected $decimal;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]
     */
    protected $errors;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'string' => 'string',
        'String' => 'string',
        'integer' => 'integer',
        'Integer' => 'integer',
        'decimal' => 'decimal',
        'Decimal' => 'decimal',
        'errors' => 'errors',
        'Errors' => 'errors',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::STRING => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'string',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::INTEGER => [
            'type' => 'int',
            'type_shim' => null,
            'name_underscore' => 'integer',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::DECIMAL => [
            'type' => 'Spryker\DecimalObject\Decimal',
            'type_shim' => null,
            'name_underscore' => 'decimal',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ERRORS => [
            'type' => 'Generated\Shared\Transfer\MessageTransfer',
            'type_shim' => null,
            'name_underscore' => 'errors',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
    ];

    /**
     * @module AopSdk
     *
     * @param string|null $string
     *
     * @return $this
     */
    public function setString($string)
    {
        $this->string = $string;
        $this->modifiedProperties[self::STRING] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $string
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setStringOrFail($string)
    {
        if ($string === null) {
            $this->throwNullValueException(static::STRING);
        }

        return $this->setString($string);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getStringOrFail()
    {
        if ($this->string === null) {
            $this->throwNullValueException(static::STRING);
        }

        return $this->string;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireString()
    {
        $this->assertPropertyIsSet(self::STRING);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param int|null $integer
     *
     * @return $this
     */
    public function setInteger($integer)
    {
        $this->integer = $integer;
        $this->modifiedProperties[self::INTEGER] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return int|null
     */
    public function getInteger()
    {
        return $this->integer;
    }

    /**
     * @module AopSdk
     *
     * @param int|null $integer
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setIntegerOrFail($integer)
    {
        if ($integer === null) {
            $this->throwNullValueException(static::INTEGER);
        }

        return $this->setInteger($integer);
    }

    /**
     * @module AopSdk
     *
     * @return int
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getIntegerOrFail()
    {
        if ($this->integer === null) {
            $this->throwNullValueException(static::INTEGER);
        }

        return $this->integer;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireInteger()
    {
        $this->assertPropertyIsSet(self::INTEGER);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param string|int|float|\Spryker\DecimalObject\Decimal|null $decimal
     *
     * @return $this
     */
    public function setDecimal($decimal = null)
    {
        if ($decimal !== null && !$decimal instanceof Decimal) {
            $decimal = new Decimal($decimal);
        }

        $this->decimal = $decimal;
        $this->modifiedProperties[self::DECIMAL] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    public function getDecimal()
    {
        return $this->decimal;
    }

    /**
     * @module AopSdk
     *
     * @param string|int|float|\Spryker\DecimalObject\Decimal $decimal
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setDecimalOrFail($decimal)
    {
        if ($decimal === null) {
            $this->throwNullValueException(static::DECIMAL);
        }

        return $this->setDecimal($decimal);
    }

    /**
     * @module AopSdk
     *
     * @return \Spryker\DecimalObject\Decimal
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getDecimalOrFail()
    {
        if ($this->decimal === null) {
            $this->throwNullValueException(static::DECIMAL);
        }

        return $this->decimal;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireDecimal()
    {
        $this->assertPropertyIsSet(self::DECIMAL);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $errors
     *
     * @return $this
     */
    public function setErrors(ArrayObject $errors)
    {
        $this->errors = $errors;
        $this->modifiedProperties[self::ERRORS] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\MessageTransfer $error
     *
     * @return $this
     */
    public function addError(MessageTransfer $error)
    {
        $this->errors[] = $error;
        $this->modifiedProperties[self::ERRORS] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireErrors()
    {
        $this->assertCollectionPropertyIsSet(self::ERRORS);

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @return $this
     * @throws \InvalidArgumentException
     *
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'string':
                case 'integer':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'errors':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value,
                        $ignoreMissingProperty);
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'decimal':
                    $this->assignValueObject($normalizedPropertyName, $value);

                    break;
                default:
                    if (!$ignoreMissingProperty) {
                        throw new \InvalidArgumentException(sprintf('Missing property `%s` in `%s`', $property,
                            static::class));
                    }
            }
        }

        return $this;
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayRecursiveCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveNotCamelCased();
        }
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->toArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->toArrayRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->toArrayNotRecursiveNotCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->toArrayNotRecursiveCamelCased();
        }
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->modifiedToArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->toArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, true);

                continue;
            }
            switch ($property) {
                case 'string':
                case 'integer':
                case 'decimal':
                    $values[$arrayKey] = $value;

                    break;
                case 'errors':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, true) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, false);

                continue;
            }
            switch ($property) {
                case 'string':
                case 'integer':
                case 'decimal':
                    $values[$arrayKey] = $value;

                    break;
                case 'errors':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, false) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return void
     */
    protected function initCollectionProperties(): void
    {
        $this->errors = $this->errors ?: new ArrayObject();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'string' => $this->string,
            'integer' => $this->integer,
            'errors' => $this->errors,
            'decimal' => $this->decimal,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'string' => $this->string,
            'integer' => $this->integer,
            'errors' => $this->errors,
            'decimal' => $this->decimal,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'string' => $this->string instanceof AbstractTransfer ? $this->string->toArray(true, false) : $this->string,
            'integer' => $this->integer instanceof AbstractTransfer ? $this->integer->toArray(true,
                false) : $this->integer,
            'errors' => $this->errors instanceof AbstractTransfer ? $this->errors->toArray(true,
                false) : $this->addValuesToCollection($this->errors, true, false),
            'decimal' => $this->decimal,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'string' => $this->string instanceof AbstractTransfer ? $this->string->toArray(true, true) : $this->string,
            'integer' => $this->integer instanceof AbstractTransfer ? $this->integer->toArray(true,
                true) : $this->integer,
            'errors' => $this->errors instanceof AbstractTransfer ? $this->errors->toArray(true,
                true) : $this->addValuesToCollection($this->errors, true, true),
            'decimal' => $this->decimal,
        ];
    }
}
