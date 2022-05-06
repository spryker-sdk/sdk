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
class OpenApiRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const TARGET_FILE = 'targetFile';

    /**
     * @var string
     */
    public const OPEN_API = 'openApi';

    /**
     * @var string|null
     */
    protected $targetFile;

    /**
     * @var \Generated\Shared\Transfer\OpenApiTransfer|null
     */
    protected $openApi;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'target_file' => 'targetFile',
        'targetFile' => 'targetFile',
        'TargetFile' => 'targetFile',
        'open_api' => 'openApi',
        'openApi' => 'openApi',
        'OpenApi' => 'openApi',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::TARGET_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'target_file',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::OPEN_API => [
            'type' => 'Generated\Shared\Transfer\OpenApiTransfer',
            'type_shim' => null,
            'name_underscore' => 'open_api',
            'is_collection' => false,
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
     * @param string|null $targetFile
     *
     * @return $this
     */
    public function setTargetFile($targetFile)
    {
        $this->targetFile = $targetFile;
        $this->modifiedProperties[self::TARGET_FILE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getTargetFile()
    {
        return $this->targetFile;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $targetFile
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setTargetFileOrFail($targetFile)
    {
        if ($targetFile === null) {
            $this->throwNullValueException(static::TARGET_FILE);
        }

        return $this->setTargetFile($targetFile);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getTargetFileOrFail()
    {
        if ($this->targetFile === null) {
            $this->throwNullValueException(static::TARGET_FILE);
        }

        return $this->targetFile;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireTargetFile()
    {
        $this->assertPropertyIsSet(self::TARGET_FILE);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\OpenApiTransfer|null $openApi
     *
     * @return $this
     */
    public function setOpenApi(OpenApiTransfer $openApi = null)
    {
        $this->openApi = $openApi;
        $this->modifiedProperties[self::OPEN_API] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\OpenApiTransfer|null
     */
    public function getOpenApi()
    {
        return $this->openApi;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\OpenApiTransfer $openApi
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setOpenApiOrFail(OpenApiTransfer $openApi)
    {
        return $this->setOpenApi($openApi);
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\OpenApiTransfer
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getOpenApiOrFail()
    {
        if ($this->openApi === null) {
            $this->throwNullValueException(static::OPEN_API);
        }

        return $this->openApi;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireOpenApi()
    {
        $this->assertPropertyIsSet(self::OPEN_API);

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
                case 'targetFile':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'openApi':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $value */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }

                    if ($value !== null && $this->isPropertyStrict($normalizedPropertyName)) {
                        $this->assertInstanceOfTransfer($normalizedPropertyName, $value);
                    }
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
                case 'targetFile':
                    $values[$arrayKey] = $value;

                    break;
                case 'openApi':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true,
                        true) : $value;

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
                case 'targetFile':
                    $values[$arrayKey] = $value;

                    break;
                case 'openApi':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true,
                        false) : $value;

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
            'targetFile' => $this->targetFile,
            'openApi' => $this->openApi,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'target_file' => $this->targetFile,
            'open_api' => $this->openApi,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'target_file' => $this->targetFile instanceof AbstractTransfer ? $this->targetFile->toArray(true,
                false) : $this->targetFile,
            'open_api' => $this->openApi instanceof AbstractTransfer ? $this->openApi->toArray(true,
                false) : $this->openApi,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'targetFile' => $this->targetFile instanceof AbstractTransfer ? $this->targetFile->toArray(true,
                true) : $this->targetFile,
            'openApi' => $this->openApi instanceof AbstractTransfer ? $this->openApi->toArray(true,
                true) : $this->openApi,
        ];
    }
}
