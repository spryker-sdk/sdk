<?php

/*
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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
            $isProject
        );
    }
}
