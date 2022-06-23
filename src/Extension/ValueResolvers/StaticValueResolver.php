<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;

class StaticValueResolver extends AbstractValueResolver implements ConfigurableValueResolverInterface
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected string $alias;

    /**
     * @var string|null
     */
    protected ?string $description;

    /**
     * @var array
     */
    protected array $settingPaths;

    /**
     * @var array
     */
    protected array $choiceValues;

    /**
     * @var string|null
     */
    protected ?string $help;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'STATIC';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return $this->settingPaths;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param array<string, mixed> $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        $this->value = $values['defaultValue'] ?? null;
        $this->alias = $values['name'];
        $this->description = $values['description'];
        $this->help = $values['help'] ?? null;
        $this->type = $values['type'] ?? 'string';
        $this->settingPaths = $values['settingPaths'] ?? [];
        $this->choiceValues = $values['choiceValues'] ?? [];
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues)
    {
        if (!isset($settingValues[$this->getAlias()])) {
            throw new MissingValueException();
        }

        return $settingValues[$this->getAlias()];
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->value;
    }

    /**
     * @param array $settingValues
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $settingValues, array $resolvedValues = []): array
    {
        return $this->choiceValues;
    }
}
