<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Core\Domain\Entity\Setting as DomainSetting;
use SprykerSdk\SdkContracts\Enum\Setting as SettingEnum;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class Setting extends DomainSetting
{
    /**
     * @var int|null
     */
    protected ?int $id;

    /**
     * @param int|null $id
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     * @param string $type
     * @param string $settingType
     * @param bool $hasInitialization
     * @param bool $forceAskValue
     * @param string|null $initializationDescription
     * @param string|null $initializer
     */
    public function __construct(
        ?int $id,
        string $path,
        $values,
        string $strategy,
        string $type = ValueTypeEnum::TYPE_STRING,
        string $settingType = SettingEnum::SETTING_TYPE_LOCAL,
        bool $hasInitialization = false,
        bool $forceAskValue = false,
        ?string $initializationDescription = null,
        ?string $initializer = null
    ) {
        $this->id = $id;
        parent::__construct(
            $path,
            $values,
            $strategy,
            $type,
            $settingType,
            $hasInitialization,
            $forceAskValue,
            $initializationDescription,
            $initializer,
        );
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param bool $hasInitialization
     *
     * @return void
     */
    public function setHasInitialization(bool $hasInitialization): void
    {
        $this->hasInitialization = $hasInitialization;
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @param string $strategy
     *
     * @return void
     */
    public function setStrategy(string $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * @param string $type
     *
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $settingType
     *
     * @return void
     */
    public function setSettingType(string $settingType): void
    {
        $this->settingType = $settingType;
    }

    /**
     * @param string|null $initializationDescription
     *
     * @return void
     */
    public function setInitializationDescription(?string $initializationDescription): void
    {
        $this->initializationDescription = $initializationDescription;
    }

    /**
     * @param string|null $initializer
     *
     * @return void
     */
    public function setInitializer(?string $initializer): void
    {
        $this->initializer = $initializer;
    }
}
