<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\Sdk\Core\Domain\Enum\Setting as SettingEnum;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

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
        string $type = 'string',
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
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     *
     * @return void
     */
    public function setValues($values): void
    {
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getStrategy(): string
    {
        return $this->strategy;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSettingType(): string
    {
        return $this->settingType;
    }

    /**
     * @return bool
     */
    public function hasInitialization(): bool
    {
        return $this->hasInitialization;
    }

    /**
     * @return string|null
     */
    public function getInitializationDescription(): ?string
    {
        return $this->initializationDescription;
    }

    /**
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
     * @return bool
     */
    public function isProject(): bool
    {
        return $this->getSettingType() !== SettingEnum::SETTING_TYPE_SDK;
    }

    /**
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
