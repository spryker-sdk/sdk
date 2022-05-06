<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class CheckReadinessTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const RECIPES = 'recipes';

    /**
     * @var string
     */
    public const CHECK_CONFIGURATION = 'checkConfiguration';

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\RecipeTransfer[]
     */
    protected $recipes;

    /**
     * @var \Generated\Shared\Transfer\CheckConfigurationTransfer|null
     */
    protected $checkConfiguration;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'recipes' => 'recipes',
        'Recipes' => 'recipes',
        'check_configuration' => 'checkConfiguration',
        'checkConfiguration' => 'checkConfiguration',
        'CheckConfiguration' => 'checkConfiguration',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::RECIPES => [
            'type' => 'Generated\Shared\Transfer\RecipeTransfer',
            'type_shim' => null,
            'name_underscore' => 'recipes',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::CHECK_CONFIGURATION => [
            'type' => 'Generated\Shared\Transfer\CheckConfigurationTransfer',
            'type_shim' => null,
            'name_underscore' => 'check_configuration',
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
     * @param \ArrayObject|\Generated\Shared\Transfer\RecipeTransfer[] $recipes
     *
     * @return $this
     */
    public function setRecipes(ArrayObject $recipes)
    {
        $this->recipes = $recipes;
        $this->modifiedProperties[self::RECIPES] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RecipeTransfer[]
     */
    public function getRecipes()
    {
        return $this->recipes;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\RecipeTransfer $recipe
     *
     * @return $this
     */
    public function addRecipe(RecipeTransfer $recipe)
    {
        $this->recipes[] = $recipe;
        $this->modifiedProperties[self::RECIPES] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     */
    public function requireRecipes()
    {
        $this->assertCollectionPropertyIsSet(self::RECIPES);

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\CheckConfigurationTransfer|null $checkConfiguration
     *
     * @return $this
     */
    public function setCheckConfiguration(CheckConfigurationTransfer $checkConfiguration = null)
    {
        $this->checkConfiguration = $checkConfiguration;
        $this->modifiedProperties[self::CHECK_CONFIGURATION] = true;

        return $this;
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\CheckConfigurationTransfer|null
     */
    public function getCheckConfiguration()
    {
        return $this->checkConfiguration;
    }

    /**
     * @module AopSdk
     *
     * @param \Generated\Shared\Transfer\CheckConfigurationTransfer $checkConfiguration
     *
     * @return $this
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function setCheckConfigurationOrFail(CheckConfigurationTransfer $checkConfiguration)
    {
        return $this->setCheckConfiguration($checkConfiguration);
    }

    /**
     * @module AopSdk
     *
     * @return \Generated\Shared\Transfer\CheckConfigurationTransfer
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     */
    public function getCheckConfigurationOrFail()
    {
        if ($this->checkConfiguration === null) {
            $this->throwNullValueException(static::CHECK_CONFIGURATION);
        }

        return $this->checkConfiguration;
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
                case 'checkConfiguration':
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
                case 'recipes':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value,
                        $ignoreMissingProperty);
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
                case 'checkConfiguration':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true,
                        true) : $value;

                    break;
                case 'recipes':
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
                case 'checkConfiguration':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true,
                        false) : $value;

                    break;
                case 'recipes':
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
        $this->recipes = $this->recipes ?: new ArrayObject();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'checkConfiguration' => $this->checkConfiguration,
            'recipes' => $this->recipes,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'check_configuration' => $this->checkConfiguration,
            'recipes' => $this->recipes,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'check_configuration' => $this->checkConfiguration instanceof AbstractTransfer ? $this->checkConfiguration->toArray(true,
                false) : $this->checkConfiguration,
            'recipes' => $this->recipes instanceof AbstractTransfer ? $this->recipes->toArray(true,
                false) : $this->addValuesToCollection($this->recipes, true, false),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'checkConfiguration' => $this->checkConfiguration instanceof AbstractTransfer ? $this->checkConfiguration->toArray(true,
                true) : $this->checkConfiguration,
            'recipes' => $this->recipes instanceof AbstractTransfer ? $this->recipes->toArray(true,
                true) : $this->addValuesToCollection($this->recipes, true, true),
        ];
    }
}
