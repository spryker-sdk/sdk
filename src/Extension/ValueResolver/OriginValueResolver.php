<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;

class OriginValueResolver extends AbstractValueResolver implements ConfigurableValueResolverInterface
{
    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var string|null
     */
    protected ?string $alias = null;

    /**
     * @var string|null
     */
    protected ?string $option = null;

    /**
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * @var array
     */
    protected array $settingPaths = [];

    /**
     * @var array
     */
    protected array $choiceValues = [];

    /**
     * @var string|null
     */
    protected ?string $help = null;

    /**
     * @var string
     */
    protected string $type = ValueTypeEnum::TYPE_STRING;

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'ORIGIN';
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
    public function getHelp(): ?string
    {
        return $this->help;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @return string|null
     */
    public function getOption(): ?string
    {
        return $this->option;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
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

    /**
     * @param array<string, mixed> $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        $this->alias = $values['alias'] ?? $values['name'] ?? null;
        $this->description = $values['description'] ?? '';
        $this->option = $values['option'] ?? null;
        $this->defaultValue = $values['defaultValue'] ?? null;
        $this->help = $values['help'] ?? null;
        $this->type = $values['type'] ?? ValueTypeEnum::TYPE_STRING;
        $this->settingPaths = $values['settingPaths'] ?? [];
        $this->choiceValues = $values['choiceValues'] ?? [];
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function formatValue(string $value): string
    {
        if ($this->getOption() === null) {
            return $value;
        }

        return sprintf('--%s=%s', $this->getOption(), $value);
    }
}
