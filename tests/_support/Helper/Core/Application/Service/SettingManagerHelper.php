<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Core\Application\Service;

use Codeception\Module;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as EntitySetting;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class SettingManagerHelper extends Module
{
    /**
     * @param string $path
     * @param mixed $value
     * @param string $strategy
     * @param string $settingType
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function createSetting(string $path, $value, string $strategy = SettingInterface::STRATEGY_REPLACE, string $settingType = 'local'): SettingInterface
    {
        $type = gettype($value);
        $type = ['boolean' => ValueTypeEnum::TYPE_BOOL, 'integer' => ValueTypeEnum::TYPE_INT][$type] ?? $type;

        return new Setting(
            $path,
            $value ?? null,
            $strategy,
            $type,
            $settingType,
        );
    }

    /**
     * @param string $path
     * @param mixed $values
     * @param int|null $id
     * @param string $strategy
     * @param string $type
     * @param string $settingType
     * @param bool $hasInitialization
     * @param string|null $initializationDescription
     * @param string|null $initializer
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function createInfrastructureSetting(
        string $path,
        $values,
        ?int $id = null,
        string $strategy = SettingInterface::STRATEGY_REPLACE,
        string $type = 'string',
        string $settingType = 'local',
        bool $hasInitialization = false,
        ?string $initializationDescription = null,
        ?string $initializer = null
    ): SettingInterface {
        return new EntitySetting(
            $id,
            $path,
            $values,
            $strategy,
            $type,
            $settingType,
            $hasInitialization,
            $initializationDescription,
            $initializer,
        );
    }
}
