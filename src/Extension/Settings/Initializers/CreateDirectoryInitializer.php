<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Settings\Initializers;

use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Contracts\Setting\SettingInitializerInterface;

class CreateDirectoryInitializer implements SettingInitializerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\SettingInterface $setting
     *
     * @return void
     */
    public function initialize(SettingInterface $setting): void
    {
        $reportPath = $setting->getValues();

        if (!is_dir($reportPath)) {
            mkdir($reportPath, 0777);
        }
    }
}
