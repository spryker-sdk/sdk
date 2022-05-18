<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Dependency\Setting;

use SprykerSdk\SdkContracts\Entity\SettingInterface;

interface SettingChoicesProviderInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return array<string>
     */
    public function getChoices(SettingInterface $setting): array;
}
