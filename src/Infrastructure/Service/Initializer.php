<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskYamlRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;

class Initializer implements InitializerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskYamlRepositoryInterface
     */
    protected TaskYamlRepositoryInterface $taskYamlRepository;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface $taskManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskYamlRepositoryInterface $taskYamlRepository
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingRepositoryInterface $settingRepository,
        TaskManagerInterface $taskManager,
        TaskYamlRepositoryInterface $taskYamlRepository
    ) {
        $this->settingRepository = $settingRepository;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->taskYamlRepository = $taskYamlRepository;
        $this->taskManager = $taskManager;
    }

    /**
     * @param array<string, mixed> $settings
     *
     * @return void
     */
    public function initialize(array $settings): void
    {
        $this->initializeSettingValues($settings, $this->settingRepository->initSettingDefinition());
        $this->taskManager->initialize($this->taskYamlRepository->findAll());
    }

    /**
     * @param array<string, mixed> $settings
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settingEntities
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function initializeSettingValues(array $settings, array $settingEntities): array
    {
        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting> $coreEntities */
        $coreEntities = array_filter($settingEntities, function (EntitySettingInterface $setting): bool {
            return $setting->getSettingType() === SettingRepositoryInterface::SDK_SETTING_TYPE;
        });

        foreach ($coreEntities as $settingEntity) {
            $this->initializeSettingValue($settingEntity, $settings);
        }

        return $coreEntities;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $settingEntity
     * @param array<string, mixed> $settings
     *
     * @return void
     */
    protected function initializeSettingValue(SettingInterface $settingEntity, array $settings): void
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
        $settingEntity->setValues($value);

        if ($value !== $settingEntity->getValues()) {
            $this->settingRepository->save($settingEntity);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $settingEntity
     *
     * @return mixed
     */
    protected function receiveValue(SettingInterface $settingEntity)
    {
        $value = $this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                $settingEntity->getInitializationDescription() ?? 'Initial value for ' . $settingEntity->getPath(),
                $settingEntity->getValues(),
                $settingEntity->getType(),
            ),
        );

        return is_scalar($value) ? $value : json_encode($value);
    }
}
