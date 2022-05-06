<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class AppConfigurationRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const PROPERTIES = 'properties';

    /**
     * @var string
     */
    public const REQUIRED = 'required';

    /**
     * @var string
     */
    public const FIELDSETS = 'fieldsets';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE = 'configurationFile';

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var array
     */
    protected $required = [];

    /**
     * @var array
     */
    protected $fieldsets = [];

    /**
     * @var string|null
     */
    protected $configurationFile;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'properties' => 'properties',
        'Properties' => 'properties',
        'required' => 'required',
        'Required' => 'required',
        'fieldsets' => 'fieldsets',
        'Fieldsets' => 'fieldsets',
        'configuration_file' => 'configurationFile',
        'configurationFile' => 'configurationFile',
        'ConfigurationFile' => 'configurationFile',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::PROPERTIES => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'properties',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::REQUIRED => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'required',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::FIELDSETS => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'fieldsets',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CONFIGURATION_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'configuration_file',
            'is_collection' => false,
            'is_transfer' => false,
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
     * @param array|null $properties
     *
     * @return $this
     */
    public function setProperties(array $properties = null)
    {
        if ($properties === null) {
            $properties = [];
        }

        $this->properties = $properties;
        $this->modifiedProperties[self::PROPERTIES] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @module AopSdk
     *
     * @param mixed $properties
     *
     * @return $this
     */
    public function addProperties($properties)
    {
        $this->properties[] = $properties;
        $this->modifiedProperties[self::PROPERTIES] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireProperties()
    {
        $this->assertPropertyIsSet(self::PROPERTIES);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param array|null $required
     *
     * @return $this
     */
    public function setRequired(array $required = null)
    {
        if ($required === null) {
            $required = [];
        }

        $this->required = $required;
        $this->modifiedProperties[self::REQUIRED] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return array
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @module AopSdk
     *
     * @param mixed $required
     *
     * @return $this
     */
    public function addRequired($required)
    {
        $this->required[] = $required;
        $this->modifiedProperties[self::REQUIRED] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireRequired()
    {
        $this->assertPropertyIsSet(self::REQUIRED);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param array|null $fieldsets
     *
     * @return $this
     */
    public function setFieldsets(array $fieldsets = null)
    {
        if ($fieldsets === null) {
            $fieldsets = [];
        }

        $this->fieldsets = $fieldsets;
        $this->modifiedProperties[self::FIELDSETS] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return array
     */
    public function getFieldsets()
    {
        return $this->fieldsets;
    }

    /**
     * @module AopSdk
     *
     * @param mixed $fieldsets
     *
     * @return $this
     */
    public function addFieldsets($fieldsets)
    {
        $this->fieldsets[] = $fieldsets;
        $this->modifiedProperties[self::FIELDSETS] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireFieldsets()
    {
        $this->assertPropertyIsSet(self::FIELDSETS);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $configurationFile
     *
     * @return $this
     */
    public function setConfigurationFile($configurationFile)
    {
        $this->configurationFile = $configurationFile;
        $this->modifiedProperties[self::CONFIGURATION_FILE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getConfigurationFile()
    {
        return $this->configurationFile;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $configurationFile
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setConfigurationFileOrFail($configurationFile)
    {
        if ($configurationFile === null) {
            $this->throwNullValueException(static::CONFIGURATION_FILE);
        }

        return $this->setConfigurationFile($configurationFile);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getConfigurationFileOrFail()
    {
        if ($this->configurationFile === null) {
            $this->throwNullValueException(static::CONFIGURATION_FILE);
        }

        return $this->configurationFile;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireConfigurationFile()
    {
        $this->assertPropertyIsSet(self::CONFIGURATION_FILE);

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
                case 'properties':
                case 'required':
                case 'fieldsets':
                case 'configurationFile':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

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
                case 'properties':
                case 'required':
                case 'fieldsets':
                case 'configurationFile':
                    $values[$arrayKey] = $value;

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
                case 'properties':
                case 'required':
                case 'fieldsets':
                case 'configurationFile':
                    $values[$arrayKey] = $value;

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
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'properties' => $this->properties,
            'required' => $this->required,
            'fieldsets' => $this->fieldsets,
            'configurationFile' => $this->configurationFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'properties' => $this->properties,
            'required' => $this->required,
            'fieldsets' => $this->fieldsets,
            'configuration_file' => $this->configurationFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'properties' => $this->properties instanceof AbstractTransfer ? $this->properties->toArray(true,
                false) : $this->properties,
            'required' => $this->required instanceof AbstractTransfer ? $this->required->toArray(true,
                false) : $this->required,
            'fieldsets' => $this->fieldsets instanceof AbstractTransfer ? $this->fieldsets->toArray(true,
                false) : $this->fieldsets,
            'configuration_file' => $this->configurationFile instanceof AbstractTransfer ? $this->configurationFile->toArray(true,
                false) : $this->configurationFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'properties' => $this->properties instanceof AbstractTransfer ? $this->properties->toArray(true,
                true) : $this->properties,
            'required' => $this->required instanceof AbstractTransfer ? $this->required->toArray(true,
                true) : $this->required,
            'fieldsets' => $this->fieldsets instanceof AbstractTransfer ? $this->fieldsets->toArray(true,
                true) : $this->fieldsets,
            'configurationFile' => $this->configurationFile instanceof AbstractTransfer ? $this->configurationFile->toArray(true,
                true) : $this->configurationFile,
        ];
    }
}
