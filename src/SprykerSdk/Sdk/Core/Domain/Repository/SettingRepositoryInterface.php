<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface;

interface SettingRepositoryInterface
{
    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface;

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface>
     */
    public function findProjectSettings(): array;
}
