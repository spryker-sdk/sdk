<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\Setting as SettingEnum;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class Setting implements SettingInterface
{
    /**
     * @var string
     */
    protected string $path;

    /**
     * @var mixed
     */
    protected $values;

    /**
     * @var string
     */
    protected string $strategy;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $settingType;

    /**
     * @var bool
     */
    protected bool $hasInitialization;

    /**
     * @var string|null
     */
    protected ?string $initializationDescription;

    /**
     * @var string|null
     */
    protected ?string $initializer;

    /**
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     * @param string $type
     * @param string $settingType
     * @param bool $hasInitialization
     * @param string|null $initializationDescription
     * @param string|null $initializer
     */
    public function __construct(
        string $path,
        $values,
        string $strategy = self::STRATEGY_REPLACE,
        string $type = ValueTypeEnum::TYPE_STRING,
        string $settingType = SettingEnum::SETTING_TYPE_LOCAL,
        bool $hasInitialization = false,
        ?string $initializationDescription = null,
        ?string $initializer = null
    ) {
        $this->initializationDescription = $initializationDescription;
        $this->hasInitialization = $hasInitialization;
        $this->settingType = $settingType;
        $this->type = $type;
        $this->strategy = $strategy;
        $this->values = $values;
        $this->path = $path;
        $this->initializer = $initializer;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     *
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $values
     *
     * @return void
     */
    public function setValues($values): void
    {
        $this->values = $values;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getStrategy(): string
    {
        return $this->strategy;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getSettingType(): string
    {
        return $this->settingType;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function hasInitialization(): bool
    {
        return $this->hasInitialization;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getInitializationDescription(): ?string
    {
        return $this->initializationDescription;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getInitializer(): ?string
    {
        return $this->initializer;
    }

    /**
     * @return bool
     */
    public function isSdk(): bool
    {
        return $this->getSettingType() === SettingEnum::SETTING_TYPE_SDK;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isProject(): bool
    {
        return $this->getSettingType() !== SettingEnum::SETTING_TYPE_SDK;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isShared(): bool
    {
        return $this->getSettingType() === SettingEnum::SETTING_TYPE_SHARED;
    }

    /**
     * @return bool
     */
    public function isLocal(): bool
    {
        return $this->getSettingType() === SettingEnum::SETTING_TYPE_LOCAL;
    }
}
