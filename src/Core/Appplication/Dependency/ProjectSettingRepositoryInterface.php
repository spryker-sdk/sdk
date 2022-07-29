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
     * Type for shared settings in repository.
     *
     * @var string
     */
    public const SHARED_SETTING_TYPE = 'shared';

    /**
     * Type for local settings.
     *
     * @var string
     */
    public const LOCAL_SETTING_TYPE = 'shared';

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array;
}
