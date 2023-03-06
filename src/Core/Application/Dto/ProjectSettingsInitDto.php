<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto;

class ProjectSettingsInitDto
{
    /**
     * @var array<string, mixed>
     */
    protected array $settingValues;

    /**
     * @var bool
     */
    protected bool $useDefaultValue;

    /**
     * @param array<string, mixed> $settingValues key is setting name
     * @param bool $useDefaultValue
     */
    public function __construct(array $settingValues, bool $useDefaultValue)
    {
        $this->settingValues = $settingValues;
        $this->useDefaultValue = $useDefaultValue;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSettingValues(): array
    {
        return $this->settingValues;
    }

    /**
     * @return bool
     */
    public function useDefaultValue(): bool
    {
        return $this->useDefaultValue;
    }

    /**
     * @param string $settingName
     *
     * @return mixed|null
     */
    public function getSettingValue(string $settingName)
    {
        return $this->settingValues[$settingName] ?? null;
    }
}
