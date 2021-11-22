<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\SettingInterface;

interface SettingRepositoryInterface
{
    /**
     * @param string $settingPath
     *
     * @return SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface;

    /**
     * @param SettingInterface $setting
     *
     * @return SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface;

    /**
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
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
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting> $settings
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    public function saveMultiple(array $settings): array;
}