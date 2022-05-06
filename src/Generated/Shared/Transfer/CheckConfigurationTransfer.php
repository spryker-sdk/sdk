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
class CheckConfigurationTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const PROJECT_NAMESPACE = 'projectNamespace';

    /**
     * @var string
     */
    public const ROOT_PATH = 'rootPath';

    /**
     * @var string
     */
    public const CHECK_CONFIGURATION = 'checkConfiguration';

    /**
     * @var string|null
     */
    protected $projectNamespace;

    /**
     * @var string|null
     */
    protected $rootPath;

    /**
     * @var array
     */
    protected $checkConfiguration = [];

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'project_namespace' => 'projectNamespace',
        'projectNamespace' => 'projectNamespace',
        'ProjectNamespace' => 'projectNamespace',
        'root_path' => 'rootPath',
        'rootPath' => 'rootPath',
        'RootPath' => 'rootPath',
        'check_configuration' => 'checkConfiguration',
        'checkConfiguration' => 'checkConfiguration',
        'CheckConfiguration' => 'checkConfiguration',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::PROJECT_NAMESPACE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'project_namespace',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ROOT_PATH => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'root_path',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CHECK_CONFIGURATION => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'check_configuration',
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
     * @param string|null $projectNamespace
     *
     * @return $this
     */
    public function setProjectNamespace($projectNamespace)
    {
        $this->projectNamespace = $projectNamespace;
        $this->modifiedProperties[self::PROJECT_NAMESPACE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getProjectNamespace()
    {
        return $this->projectNamespace;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $projectNamespace
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setProjectNamespaceOrFail($projectNamespace)
    {
        if ($projectNamespace === null) {
            $this->throwNullValueException(static::PROJECT_NAMESPACE);
        }

        return $this->setProjectNamespace($projectNamespace);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getProjectNamespaceOrFail()
    {
        if ($this->projectNamespace === null) {
            $this->throwNullValueException(static::PROJECT_NAMESPACE);
        }

        return $this->projectNamespace;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireProjectNamespace()
    {
        $this->assertPropertyIsSet(self::PROJECT_NAMESPACE);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $rootPath
     *
     * @return $this
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;
        $this->modifiedProperties[self::ROOT_PATH] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $rootPath
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setRootPathOrFail($rootPath)
    {
        if ($rootPath === null) {
            $this->throwNullValueException(static::ROOT_PATH);
        }

        return $this->setRootPath($rootPath);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getRootPathOrFail()
    {
        if ($this->rootPath === null) {
            $this->throwNullValueException(static::ROOT_PATH);
        }

        return $this->rootPath;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireRootPath()
    {
        $this->assertPropertyIsSet(self::ROOT_PATH);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param array|null $checkConfiguration
     *
     * @return $this
     */
    public function setCheckConfiguration(array $checkConfiguration = null)
    {
        if ($checkConfiguration === null) {
            $checkConfiguration = [];
        }

        $this->checkConfiguration = $checkConfiguration;
        $this->modifiedProperties[self::CHECK_CONFIGURATION] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return array
     */
    public function getCheckConfiguration()
    {
        return $this->checkConfiguration;
    }

    /**
     * @module AopSdk
     *
     * @param mixed $checkConfiguration
     *
     * @return $this
     */
    public function addCheckConfiguration($checkConfiguration)
    {
        $this->checkConfiguration[] = $checkConfiguration;
        $this->modifiedProperties[self::CHECK_CONFIGURATION] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireCheckConfiguration()
    {
        $this->assertPropertyIsSet(self::CHECK_CONFIGURATION);

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
                case 'projectNamespace':
                case 'rootPath':
                case 'checkConfiguration':
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
                case 'projectNamespace':
                case 'rootPath':
                case 'checkConfiguration':
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
                case 'projectNamespace':
                case 'rootPath':
                case 'checkConfiguration':
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
            'projectNamespace' => $this->projectNamespace,
            'rootPath' => $this->rootPath,
            'checkConfiguration' => $this->checkConfiguration,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'project_namespace' => $this->projectNamespace,
            'root_path' => $this->rootPath,
            'check_configuration' => $this->checkConfiguration,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'project_namespace' => $this->projectNamespace instanceof AbstractTransfer ? $this->projectNamespace->toArray(true,
                false) : $this->projectNamespace,
            'root_path' => $this->rootPath instanceof AbstractTransfer ? $this->rootPath->toArray(true,
                false) : $this->rootPath,
            'check_configuration' => $this->checkConfiguration instanceof AbstractTransfer ? $this->checkConfiguration->toArray(true,
                false) : $this->checkConfiguration,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'projectNamespace' => $this->projectNamespace instanceof AbstractTransfer ? $this->projectNamespace->toArray(true,
                true) : $this->projectNamespace,
            'rootPath' => $this->rootPath instanceof AbstractTransfer ? $this->rootPath->toArray(true,
                true) : $this->rootPath,
            'checkConfiguration' => $this->checkConfiguration instanceof AbstractTransfer ? $this->checkConfiguration->toArray(true,
                true) : $this->checkConfiguration,
        ];
    }
}
