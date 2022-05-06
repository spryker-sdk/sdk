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
class ValidateRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const MANIFEST_PATH = 'manifestPath';

    /**
     * @var string
     */
    public const CONFIGURATION_FILE = 'configurationFile';

    /**
     * @var string
     */
    public const TRANSLATION_FILE = 'translationFile';

    /**
     * @var string
     */
    public const ASYNC_API_FILE = 'asyncApiFile';

    /**
     * @var string
     */
    public const OPEN_API_FILE = 'openApiFile';

    /**
     * @var string|null
     */
    protected $manifestPath;

    /**
     * @var string|null
     */
    protected $configurationFile;

    /**
     * @var string|null
     */
    protected $translationFile;

    /**
     * @var string|null
     */
    protected $asyncApiFile;

    /**
     * @var string|null
     */
    protected $openApiFile;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'manifest_path' => 'manifestPath',
        'manifestPath' => 'manifestPath',
        'ManifestPath' => 'manifestPath',
        'configuration_file' => 'configurationFile',
        'configurationFile' => 'configurationFile',
        'ConfigurationFile' => 'configurationFile',
        'translation_file' => 'translationFile',
        'translationFile' => 'translationFile',
        'TranslationFile' => 'translationFile',
        'async_api_file' => 'asyncApiFile',
        'asyncApiFile' => 'asyncApiFile',
        'AsyncApiFile' => 'asyncApiFile',
        'open_api_file' => 'openApiFile',
        'openApiFile' => 'openApiFile',
        'OpenApiFile' => 'openApiFile',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::MANIFEST_PATH => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'manifest_path',
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
        self::TRANSLATION_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'translation_file',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ASYNC_API_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'async_api_file',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::OPEN_API_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'open_api_file',
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
     * @param string|null $manifestPath
     *
     * @return $this
     */
    public function setManifestPath($manifestPath)
    {
        $this->manifestPath = $manifestPath;
        $this->modifiedProperties[self::MANIFEST_PATH] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getManifestPath()
    {
        return $this->manifestPath;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $manifestPath
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setManifestPathOrFail($manifestPath)
    {
        if ($manifestPath === null) {
            $this->throwNullValueException(static::MANIFEST_PATH);
        }

        return $this->setManifestPath($manifestPath);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getManifestPathOrFail()
    {
        if ($this->manifestPath === null) {
            $this->throwNullValueException(static::MANIFEST_PATH);
        }

        return $this->manifestPath;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireManifestPath()
    {
        $this->assertPropertyIsSet(self::MANIFEST_PATH);

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
     * @module AopSdk
     *
     * @param string|null $translationFile
     *
     * @return $this
     */
    public function setTranslationFile($translationFile)
    {
        $this->translationFile = $translationFile;
        $this->modifiedProperties[self::TRANSLATION_FILE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getTranslationFile()
    {
        return $this->translationFile;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $translationFile
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setTranslationFileOrFail($translationFile)
    {
        if ($translationFile === null) {
            $this->throwNullValueException(static::TRANSLATION_FILE);
        }

        return $this->setTranslationFile($translationFile);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getTranslationFileOrFail()
    {
        if ($this->translationFile === null) {
            $this->throwNullValueException(static::TRANSLATION_FILE);
        }

        return $this->translationFile;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireTranslationFile()
    {
        $this->assertPropertyIsSet(self::TRANSLATION_FILE);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $asyncApiFile
     *
     * @return $this
     */
    public function setAsyncApiFile($asyncApiFile)
    {
        $this->asyncApiFile = $asyncApiFile;
        $this->modifiedProperties[self::ASYNC_API_FILE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getAsyncApiFile()
    {
        return $this->asyncApiFile;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $asyncApiFile
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setAsyncApiFileOrFail($asyncApiFile)
    {
        if ($asyncApiFile === null) {
            $this->throwNullValueException(static::ASYNC_API_FILE);
        }

        return $this->setAsyncApiFile($asyncApiFile);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getAsyncApiFileOrFail()
    {
        if ($this->asyncApiFile === null) {
            $this->throwNullValueException(static::ASYNC_API_FILE);
        }

        return $this->asyncApiFile;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireAsyncApiFile()
    {
        $this->assertPropertyIsSet(self::ASYNC_API_FILE);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $openApiFile
     *
     * @return $this
     */
    public function setOpenApiFile($openApiFile)
    {
        $this->openApiFile = $openApiFile;
        $this->modifiedProperties[self::OPEN_API_FILE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getOpenApiFile()
    {
        return $this->openApiFile;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $openApiFile
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setOpenApiFileOrFail($openApiFile)
    {
        if ($openApiFile === null) {
            $this->throwNullValueException(static::OPEN_API_FILE);
        }

        return $this->setOpenApiFile($openApiFile);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getOpenApiFileOrFail()
    {
        if ($this->openApiFile === null) {
            $this->throwNullValueException(static::OPEN_API_FILE);
        }

        return $this->openApiFile;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireOpenApiFile()
    {
        $this->assertPropertyIsSet(self::OPEN_API_FILE);

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
                case 'manifestPath':
                case 'configurationFile':
                case 'translationFile':
                case 'asyncApiFile':
                case 'openApiFile':
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
                case 'manifestPath':
                case 'configurationFile':
                case 'translationFile':
                case 'asyncApiFile':
                case 'openApiFile':
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
                case 'manifestPath':
                case 'configurationFile':
                case 'translationFile':
                case 'asyncApiFile':
                case 'openApiFile':
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
            'manifestPath' => $this->manifestPath,
            'configurationFile' => $this->configurationFile,
            'translationFile' => $this->translationFile,
            'asyncApiFile' => $this->asyncApiFile,
            'openApiFile' => $this->openApiFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'manifest_path' => $this->manifestPath,
            'configuration_file' => $this->configurationFile,
            'translation_file' => $this->translationFile,
            'async_api_file' => $this->asyncApiFile,
            'open_api_file' => $this->openApiFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'manifest_path' => $this->manifestPath instanceof AbstractTransfer ? $this->manifestPath->toArray(true,
                false) : $this->manifestPath,
            'configuration_file' => $this->configurationFile instanceof AbstractTransfer ? $this->configurationFile->toArray(true,
                false) : $this->configurationFile,
            'translation_file' => $this->translationFile instanceof AbstractTransfer ? $this->translationFile->toArray(true,
                false) : $this->translationFile,
            'async_api_file' => $this->asyncApiFile instanceof AbstractTransfer ? $this->asyncApiFile->toArray(true,
                false) : $this->asyncApiFile,
            'open_api_file' => $this->openApiFile instanceof AbstractTransfer ? $this->openApiFile->toArray(true,
                false) : $this->openApiFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'manifestPath' => $this->manifestPath instanceof AbstractTransfer ? $this->manifestPath->toArray(true,
                true) : $this->manifestPath,
            'configurationFile' => $this->configurationFile instanceof AbstractTransfer ? $this->configurationFile->toArray(true,
                true) : $this->configurationFile,
            'translationFile' => $this->translationFile instanceof AbstractTransfer ? $this->translationFile->toArray(true,
                true) : $this->translationFile,
            'asyncApiFile' => $this->asyncApiFile instanceof AbstractTransfer ? $this->asyncApiFile->toArray(true,
                true) : $this->asyncApiFile,
            'openApiFile' => $this->openApiFile instanceof AbstractTransfer ? $this->openApiFile->toArray(true,
                true) : $this->openApiFile,
        ];
    }
}
