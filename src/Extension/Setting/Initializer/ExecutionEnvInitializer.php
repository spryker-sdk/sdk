<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Setting\Initializer;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\ExecutionEnv;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;

class ExecutionEnvInitializer implements SettingInitializerInterface
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

        $setting->setValues($this->getExecutionEnv());
        $this->settingRepository->save($setting);
    }

    /**
     * @return string
     */
    protected function getExecutionEnv(): string
    {
        $ciExecution = (bool)getenv('SDK_CI_EXECUTION');

        return $ciExecution ? ExecutionEnv::CI : ExecutionEnv::DEVELOPER;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'execution_env_initializer';
    }
}
