<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Setting\Initializer;

use Ramsey\Uuid\Uuid;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Setting\SettingInitializerInterface;

class ProjectUuidInitializer implements SettingInitializerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     */
    public function __construct(ProjectSettingRepositoryInterface $projectSettingRepository)
    {
        $this->projectSettingRepository = $projectSettingRepository;
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return void
     */
    public function initialize(SettingInterface $setting): void
    {
        $existingSetting = $this->projectSettingRepository->findOneByPath($setting->getPath());

        if ($existingSetting && $existingSetting->getValues()) {
            $setting->setValues($existingSetting->getValues());

            return;
        }

        $setting->setValues(Uuid::uuid4()->toString());
        $this->projectSettingRepository->save($setting);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'project_uuid_initializer';
    }
}
