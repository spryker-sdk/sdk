<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Initializer;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;

class ConsoleBasedInitializer extends AbstractInitializer
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $settingEntity
     * @param array<string, mixed> $settings
     *
     * @return void
     */
    protected function initializeSettingValue(EntitySettingInterface $settingEntity, array $settings): void
    {
        if (!empty($settings[$settingEntity->getPath()])) {
            $settingEntity->setValues($settings[$settingEntity->getPath()]);
            $this->settingRepository->save($settingEntity);

            return;
        }

        if ($settingEntity->hasInitialization() === false || $settingEntity->getValues() !== null) {
            return;
        }

        $value = $this->receiveValue($settingEntity);

        if ($value !== $settingEntity->getValues()) {
            $settingEntity->setValues($value);
            $this->settingRepository->save($settingEntity);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $settingEntity
     *
     * @return mixed
     */
    protected function receiveValue(EntitySettingInterface $settingEntity)
    {
        $value = $this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                $settingEntity->getInitializationDescription() ?? 'Initial value for ' . $settingEntity->getPath(),
                $settingEntity->getValues(),
                $settingEntity->getType(),
            ),
        );

        return $value === null || is_scalar($value) ? $value : json_encode($value);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return bool
     */
    public function isApplicable(InitializeCriteriaDto $criteriaDto): bool
    {
        return $criteriaDto->getSourceType() === CallSource::SOURCE_TYPE_CLI;
    }
}
