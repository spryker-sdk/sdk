<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;

interface ProjectSettingRepositoryInterface extends SettingRepositoryInterface
{
    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array;
}
