<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Repository;

use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;

interface SettingRepositoryInterface
{
    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function findProjectSettings(): array;

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    public function findCoreSettings(): array;

    /**
     * @param array<string> $paths
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    public function findByPaths(array $paths): array;

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array;
}
