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
class AppTranslationRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const TRANSLATIONS = 'translations';

    /**
     * @var string
     */
    public const TRANSLATION_FILE = 'translationFile';

    /**
     * @var array
     */
    protected $translations = [];

    /**
     * @var string|null
     */
    protected $translationFile;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'translations' => 'translations',
        'Translations' => 'translations',
        'translation_file' => 'translationFile',
        'translationFile' => 'translationFile',
        'TranslationFile' => 'translationFile',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::TRANSLATIONS => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'translations',
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
    ];

    /**
     * @module AopSdk
     *
     * @param array|null $translations
     *
     * @return $this
     */
    public function setTranslations(array $translations = null)
    {
        if ($translations === null) {
            $translations = [];
        }

        $this->translations = $translations;
        $this->modifiedProperties[self::TRANSLATIONS] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @module AopSdk
     *
     * @param mixed $translations
     *
     * @return $this
     */
    public function addTranslations($translations)
    {
        $this->translations[] = $translations;
        $this->modifiedProperties[self::TRANSLATIONS] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireTranslations()
    {
        $this->assertPropertyIsSet(self::TRANSLATIONS);

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
                case 'translations':
                case 'translationFile':
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
                case 'translations':
                case 'translationFile':
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
                case 'translations':
                case 'translationFile':
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
            'translations' => $this->translations,
            'translationFile' => $this->translationFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'translations' => $this->translations,
            'translation_file' => $this->translationFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'translations' => $this->translations instanceof AbstractTransfer ? $this->translations->toArray(true,
                false) : $this->translations,
            'translation_file' => $this->translationFile instanceof AbstractTransfer ? $this->translationFile->toArray(true,
                false) : $this->translationFile,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'translations' => $this->translations instanceof AbstractTransfer ? $this->translations->toArray(true,
                true) : $this->translations,
            'translationFile' => $this->translationFile instanceof AbstractTransfer ? $this->translationFile->toArray(true,
                true) : $this->translationFile,
        ];
    }
}
