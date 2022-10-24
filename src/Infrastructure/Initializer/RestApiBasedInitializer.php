<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Initializer;

use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;

class RestApiBasedInitializer extends AbstractInitializer
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return bool
     */
    public function isApplicable(InitializeCriteriaDto $criteriaDto): bool
    {
        return $criteriaDto->getSourceType() === CallSource::SOURCE_TYPE_REST_API;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $settingEntity
     * @param array<string, mixed> $settings
     *
     * @return void
     */
    protected function initializeSettingValue(EntitySettingInterface $settingEntity, array $settings): void
    {
        // todo :: add validation before. All settings values must be set by client
        if (empty($settings[$settingEntity->getPath()])) {
            return;
        }

        $settingEntity->setValues($settings[$settingEntity->getPath()]);
        $this->settingRepository->save($settingEntity);
    }
}
