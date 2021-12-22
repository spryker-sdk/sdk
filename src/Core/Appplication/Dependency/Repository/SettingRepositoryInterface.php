<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Repository;

use SprykerSdk\SdkContracts\Entity\SettingInterface;

interface SettingRepositoryInterface
{
    /**
     * @param string $settingPath
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface|null
     */
    public function findOneByPath(string $settingPath): ?SettingInterface;

    /**
     * @param string $settingPath
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function getOneByPath(string $settingPath): SettingInterface;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function save(SettingInterface $setting): SettingInterface;

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
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
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function saveMultiple(array $settings): array;
}
