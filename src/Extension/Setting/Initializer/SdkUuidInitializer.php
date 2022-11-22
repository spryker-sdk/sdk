<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Setting\Initializer;

use Ramsey\Uuid\Uuid;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;

class SdkUuidInitializer implements SettingInitializerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     */
    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return void
     */
    public function initialize(SettingInterface $setting): void
    {
        $existingSetting = $this->settingRepository->findOneByPath($setting->getPath());

        if ($existingSetting !== null && $existingSetting->getValues()) {
            $setting->setValues($existingSetting->getValues());

            return;
        }

        $setting->setValues(Uuid::uuid4()->toString());
        $this->settingRepository->save($setting);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'sdk_uuid_initializer';
    }
}
