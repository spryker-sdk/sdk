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
class ManifestRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const MANIFEST = 'manifest';

    /**
     * @var string
     */
    public const MANIFEST_PATH = 'manifestPath';

    /**
     * @var \Generated\Shared\Transfer\ManifestTransfer|null
     */
    protected $manifest;

    /**
     * @var string|null
     */
    protected $manifestPath;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'manifest' => 'manifest',
        'Manifest' => 'manifest',
        'manifest_path' => 'manifestPath',
        'manifestPath' => 'manifestPath',
        'ManifestPath' => 'manifestPath',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::MANIFEST => [
            'type' => 'Generated\Shared\Transfer\ManifestTransfer',
            'type_shim' => null,
            'name_underscore' => 'manifest',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
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
    ];

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\ManifestTransfer|null $manifest
     *
     * @return $this
     */
    public function setManifest(ManifestTransfer $manifest = null)
    {
        $this->manifest = $manifest;
        $this->modifiedProperties[self::MANIFEST] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\ManifestTransfer|null
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\ManifestTransfer $manifest
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setManifestOrFail(ManifestTransfer $manifest)
    {
        return $this->setManifest($manifest);
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\ManifestTransfer
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getManifestOrFail()
    {
        if ($this->manifest === null) {
            $this->throwNullValueException(static::MANIFEST);
        }

        return $this->manifest;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireManifest()
    {
        $this->assertPropertyIsSet(self::MANIFEST);

        return $this;
    }

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
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'manifest':
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
                case 'manifestPath':
                    $values[$arrayKey] = $value;

                    break;
                case 'manifest':
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
                case 'manifestPath':
                    $values[$arrayKey] = $value;

                    break;
                case 'manifest':
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
            'manifestPath' => $this->manifestPath,
            'manifest' => $this->manifest,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'manifest_path' => $this->manifestPath,
            'manifest' => $this->manifest,
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
            'manifest' => $this->manifest instanceof AbstractTransfer ? $this->manifest->toArray(true,
                false) : $this->manifest,
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
            'manifest' => $this->manifest instanceof AbstractTransfer ? $this->manifest->toArray(true,
                true) : $this->manifest,
        ];
    }
}
