<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Core\Application\Service;

use Codeception\Module;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class SettingManagerHelper extends Module
{
    /**
     * @param string $path
     * @param mixed $value
     * @param string $strategy
     * @param bool $isProject
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function createSetting(string $path, $value, string $strategy = SettingInterface::STRATEGY_REPLACE, bool $isProject = true): SettingInterface
    {
        return new Setting(
            $path,
            $value ?? null,
            $strategy,
            gettype($value),
            $isProject,
        );
    }

    /**
     * @param int|null $id
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     * @param string $type
     * @param bool $isProject
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
        bool $isProject = true,
        bool $hasInitialization = false,
        ?string $initializationDescription = null,
        ?string $initializer = null
    ): SettingInterface {
        return new \SprykerSdk\Sdk\Infrastructure\Entity\Setting(
            $id,
            $path,
            $values,
            $strategy,
            $type,
            $isProject,
            $hasInitialization,
            $initializationDescription,
            $initializer
        );
    }
}
