<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Setting;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\SettingFetcherInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

class SettingFetcher implements SettingFetcherInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry
     */
    protected SettingInitializerRegistry $settingInitializerRegistry;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry $settingInitializerRegistry
     */
    public function __construct(SettingRepositoryInterface $settingRepository, SettingInitializerRegistry $settingInitializerRegistry)
    {
        $this->settingRepository = $settingRepository;
        $this->settingInitializerRegistry = $settingInitializerRegistry;
    }

    /**
     * @param string $settingPath
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    public function getOneByPath(string $settingPath): SettingInterface
    {
        $setting = $this->settingRepository->findOneByPath($settingPath);

        if ($setting === null) {
            throw new MissingSettingException(
                sprintf(
                    'Setting by path `%s` not found. You need to run `sdk:init:sdk` and `sdk:init:project` command',
                    $settingPath,
                ),
            );
        }

        $initializerName = $setting->getInitializer();

        if ($initializerName !== null && $this->settingInitializerRegistry->hasSettingInitializer($initializerName)) {
            $this->settingInitializerRegistry->getSettingInitializer($initializerName)->initialize($setting);
        }

        return $setting;
    }
}
