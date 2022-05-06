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
class AsyncApiMessageTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const NAME = 'name';

    /**
     * @var string
     */
    public const SUMMARY = 'summary';

    /**
     * @var string
     */
    public const CONTENT_TYPE = 'contentType';

    /**
     * @var string
     */
    public const PROPERTIES = 'properties';

    /**
     * @var string
     */
    public const REQUIRED_PROPERTIES = 'requiredProperties';

    /**
     * @var string
     */
    public const ADD_METADATA = 'addMetadata';

    /**
     * @var string
     */
    public const CHANNEL = 'channel';

    /**
     * @var string
     */
    public const IS_PUBLISH = 'isPublish';

    /**
     * @var string
     */
    public const IS_SUBSCRIBE = 'isSubscribe';

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $summary;

    /**
     * @var string|null
     */
    protected $contentType;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var array
     */
    protected $requiredProperties = [];

    /**
     * @var bool|null
     */
    protected $addMetadata;

    /**
     * @var \Generated\Shared\Transfer\AsyncApiChannelTransfer|null
     */
    protected $channel;

    /**
     * @var bool|null
     */
    protected $isPublish;

    /**
     * @var bool|null
     */
    protected $isSubscribe;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'name' => 'name',
        'Name' => 'name',
        'summary' => 'summary',
        'Summary' => 'summary',
        'content_type' => 'contentType',
        'contentType' => 'contentType',
        'ContentType' => 'contentType',
        'properties' => 'properties',
        'Properties' => 'properties',
        'required_properties' => 'requiredProperties',
        'requiredProperties' => 'requiredProperties',
        'RequiredProperties' => 'requiredProperties',
        'add_metadata' => 'addMetadata',
        'addMetadata' => 'addMetadata',
        'AddMetadata' => 'addMetadata',
        'channel' => 'channel',
        'Channel' => 'channel',
        'is_publish' => 'isPublish',
        'isPublish' => 'isPublish',
        'IsPublish' => 'isPublish',
        'is_subscribe' => 'isSubscribe',
        'isSubscribe' => 'isSubscribe',
        'IsSubscribe' => 'isSubscribe',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::NAME => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'name',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::SUMMARY => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'summary',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CONTENT_TYPE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'content_type',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
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
        self::REQUIRED_PROPERTIES => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'required_properties',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ADD_METADATA => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'add_metadata',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CHANNEL => [
            'type' => 'Generated\Shared\Transfer\AsyncApiChannelTransfer',
            'type_shim' => null,
            'name_underscore' => 'channel',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::IS_PUBLISH => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'is_publish',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::IS_SUBSCRIBE => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'is_subscribe',
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
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->modifiedProperties[self::NAME] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $name
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setNameOrFail($name)
    {
        if ($name === null) {
            $this->throwNullValueException(static::NAME);
        }

        return $this->setName($name);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getNameOrFail()
    {
        if ($this->name === null) {
            $this->throwNullValueException(static::NAME);
        }

        return $this->name;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireName()
    {
        $this->assertPropertyIsSet(self::NAME);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $summary
     *
     * @return $this
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        $this->modifiedProperties[self::SUMMARY] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $summary
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setSummaryOrFail($summary)
    {
        if ($summary === null) {
            $this->throwNullValueException(static::SUMMARY);
        }

        return $this->setSummary($summary);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getSummaryOrFail()
    {
        if ($this->summary === null) {
            $this->throwNullValueException(static::SUMMARY);
        }

        return $this->summary;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireSummary()
    {
        $this->assertPropertyIsSet(self::SUMMARY);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $contentType
     *
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->modifiedProperties[self::CONTENT_TYPE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return string|null
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @module AopSdk
     *
     * @param string|null $contentType
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setContentTypeOrFail($contentType)
    {
        if ($contentType === null) {
            $this->throwNullValueException(static::CONTENT_TYPE);
        }

        return $this->setContentType($contentType);
    }

    /**
     * @module AopSdk
     *
     * @return string
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getContentTypeOrFail()
    {
        if ($this->contentType === null) {
            $this->throwNullValueException(static::CONTENT_TYPE);
        }

        return $this->contentType;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireContentType()
    {
        $this->assertPropertyIsSet(self::CONTENT_TYPE);

        return $this;
    }

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
     * @param array|null $requiredProperties
     *
     * @return $this
     */
    public function setRequiredProperties(array $requiredProperties = null)
    {
        if ($requiredProperties === null) {
            $requiredProperties = [];
        }

        $this->requiredProperties = $requiredProperties;
        $this->modifiedProperties[self::REQUIRED_PROPERTIES] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return array
     */
    public function getRequiredProperties()
    {
        return $this->requiredProperties;
    }

    /**
     * @module AopSdk
     *
     * @param mixed $requiredProperties
     *
     * @return $this
     */
    public function addRequiredProperties($requiredProperties)
    {
        $this->requiredProperties[] = $requiredProperties;
        $this->modifiedProperties[self::REQUIRED_PROPERTIES] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireRequiredProperties()
    {
        $this->assertPropertyIsSet(self::REQUIRED_PROPERTIES);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param bool|null $addMetadata
     *
     * @return $this
     */
    public function setAddMetadata($addMetadata)
    {
        $this->addMetadata = $addMetadata;
        $this->modifiedProperties[self::ADD_METADATA] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return bool|null
     */
    public function getAddMetadata()
    {
        return $this->addMetadata;
    }

    /**
     * @module AopSdk
     *
     * @param bool|null $addMetadata
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setAddMetadataOrFail($addMetadata)
    {
        if ($addMetadata === null) {
            $this->throwNullValueException(static::ADD_METADATA);
        }

        return $this->setAddMetadata($addMetadata);
    }

    /**
     * @module AopSdk
     *
     * @return bool
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getAddMetadataOrFail()
    {
        if ($this->addMetadata === null) {
            $this->throwNullValueException(static::ADD_METADATA);
        }

        return $this->addMetadata;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireAddMetadata()
    {
        $this->assertPropertyIsSet(self::ADD_METADATA);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\AsyncApiChannelTransfer|null $channel
     *
     * @return $this
     */
    public function setChannel(AsyncApiChannelTransfer $channel = null)
    {
        $this->channel = $channel;
        $this->modifiedProperties[self::CHANNEL] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\AsyncApiChannelTransfer|null
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\AsyncApiChannelTransfer $channel
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setChannelOrFail(AsyncApiChannelTransfer $channel)
    {
        return $this->setChannel($channel);
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\AsyncApiChannelTransfer
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getChannelOrFail()
    {
        if ($this->channel === null) {
            $this->throwNullValueException(static::CHANNEL);
        }

        return $this->channel;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireChannel()
    {
        $this->assertPropertyIsSet(self::CHANNEL);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param bool|null $isPublish
     *
     * @return $this
     */
    public function setIsPublish($isPublish)
    {
        $this->isPublish = $isPublish;
        $this->modifiedProperties[self::IS_PUBLISH] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return bool|null
     */
    public function getIsPublish()
    {
        return $this->isPublish;
    }

    /**
     * @module AopSdk
     *
     * @param bool|null $isPublish
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setIsPublishOrFail($isPublish)
    {
        if ($isPublish === null) {
            $this->throwNullValueException(static::IS_PUBLISH);
        }

        return $this->setIsPublish($isPublish);
    }

    /**
     * @module AopSdk
     *
     * @return bool
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getIsPublishOrFail()
    {
        if ($this->isPublish === null) {
            $this->throwNullValueException(static::IS_PUBLISH);
        }

        return $this->isPublish;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireIsPublish()
    {
        $this->assertPropertyIsSet(self::IS_PUBLISH);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param bool|null $isSubscribe
     *
     * @return $this
     */
    public function setIsSubscribe($isSubscribe)
    {
        $this->isSubscribe = $isSubscribe;
        $this->modifiedProperties[self::IS_SUBSCRIBE] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return bool|null
     */
    public function getIsSubscribe()
    {
        return $this->isSubscribe;
    }

    /**
     * @module AopSdk
     *
     * @param bool|null $isSubscribe
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setIsSubscribeOrFail($isSubscribe)
    {
        if ($isSubscribe === null) {
            $this->throwNullValueException(static::IS_SUBSCRIBE);
        }

        return $this->setIsSubscribe($isSubscribe);
    }

    /**
     * @module AopSdk
     *
     * @return bool
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getIsSubscribeOrFail()
    {
        if ($this->isSubscribe === null) {
            $this->throwNullValueException(static::IS_SUBSCRIBE);
        }

        return $this->isSubscribe;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireIsSubscribe()
    {
        $this->assertPropertyIsSet(self::IS_SUBSCRIBE);

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
                case 'name':
                case 'summary':
                case 'contentType':
                case 'properties':
                case 'requiredProperties':
                case 'addMetadata':
                case 'isPublish':
                case 'isSubscribe':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'channel':
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
                case 'name':
                case 'summary':
                case 'contentType':
                case 'properties':
                case 'requiredProperties':
                case 'addMetadata':
                case 'isPublish':
                case 'isSubscribe':
                    $values[$arrayKey] = $value;

                    break;
                case 'channel':
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
                case 'name':
                case 'summary':
                case 'contentType':
                case 'properties':
                case 'requiredProperties':
                case 'addMetadata':
                case 'isPublish':
                case 'isSubscribe':
                    $values[$arrayKey] = $value;

                    break;
                case 'channel':
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
            'name' => $this->name,
            'summary' => $this->summary,
            'contentType' => $this->contentType,
            'properties' => $this->properties,
            'requiredProperties' => $this->requiredProperties,
            'addMetadata' => $this->addMetadata,
            'isPublish' => $this->isPublish,
            'isSubscribe' => $this->isSubscribe,
            'channel' => $this->channel,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'name' => $this->name,
            'summary' => $this->summary,
            'content_type' => $this->contentType,
            'properties' => $this->properties,
            'required_properties' => $this->requiredProperties,
            'add_metadata' => $this->addMetadata,
            'is_publish' => $this->isPublish,
            'is_subscribe' => $this->isSubscribe,
            'channel' => $this->channel,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'name' => $this->name instanceof AbstractTransfer ? $this->name->toArray(true, false) : $this->name,
            'summary' => $this->summary instanceof AbstractTransfer ? $this->summary->toArray(true,
                false) : $this->summary,
            'content_type' => $this->contentType instanceof AbstractTransfer ? $this->contentType->toArray(true,
                false) : $this->contentType,
            'properties' => $this->properties instanceof AbstractTransfer ? $this->properties->toArray(true,
                false) : $this->properties,
            'required_properties' => $this->requiredProperties instanceof AbstractTransfer ? $this->requiredProperties->toArray(true,
                false) : $this->requiredProperties,
            'add_metadata' => $this->addMetadata instanceof AbstractTransfer ? $this->addMetadata->toArray(true,
                false) : $this->addMetadata,
            'is_publish' => $this->isPublish instanceof AbstractTransfer ? $this->isPublish->toArray(true,
                false) : $this->isPublish,
            'is_subscribe' => $this->isSubscribe instanceof AbstractTransfer ? $this->isSubscribe->toArray(true,
                false) : $this->isSubscribe,
            'channel' => $this->channel instanceof AbstractTransfer ? $this->channel->toArray(true,
                false) : $this->channel,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'name' => $this->name instanceof AbstractTransfer ? $this->name->toArray(true, true) : $this->name,
            'summary' => $this->summary instanceof AbstractTransfer ? $this->summary->toArray(true,
                true) : $this->summary,
            'contentType' => $this->contentType instanceof AbstractTransfer ? $this->contentType->toArray(true,
                true) : $this->contentType,
            'properties' => $this->properties instanceof AbstractTransfer ? $this->properties->toArray(true,
                true) : $this->properties,
            'requiredProperties' => $this->requiredProperties instanceof AbstractTransfer ? $this->requiredProperties->toArray(true,
                true) : $this->requiredProperties,
            'addMetadata' => $this->addMetadata instanceof AbstractTransfer ? $this->addMetadata->toArray(true,
                true) : $this->addMetadata,
            'isPublish' => $this->isPublish instanceof AbstractTransfer ? $this->isPublish->toArray(true,
                true) : $this->isPublish,
            'isSubscribe' => $this->isSubscribe instanceof AbstractTransfer ? $this->isSubscribe->toArray(true,
                true) : $this->isSubscribe,
            'channel' => $this->channel instanceof AbstractTransfer ? $this->channel->toArray(true,
                true) : $this->channel,
        ];
    }
}
