<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\Setting;

interface SettingRepositoryInterface
{
    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting|null
     */
    public function findOneByPath(string $settingPath): ?Setting;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Setting $setting
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting
     */
    public function save(Setting $setting): Setting;

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    public function findProjectSettings(): array;
}