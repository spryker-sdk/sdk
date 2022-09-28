<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;

class Initializer implements InitializerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface
     */
    protected TaskYamlFileLoaderInterface $taskYamlRepository;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $cliValueReceiver
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface $taskManager
     * @param \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface $taskYamlRepository
     */
    public function __construct(
        InteractionProcessorInterface $cliValueReceiver,
        SettingRepositoryInterface $settingRepository,
        TaskManagerInterface $taskManager,
        TaskYamlFileLoaderInterface $taskYamlRepository
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
        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingDefinition */
        $settingDefinition = $this->settingRepository->initSettingDefinition();

        $this->initializeSettingValues($settings, $settingDefinition);
        $this->taskManager->initialize($this->taskYamlRepository->loadAll());
    }

    /**
     * @param array<string, mixed> $settings
     * @param array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingEntities
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function initializeSettingValues(array $settings, array $settingEntities): array
    {
        $coreEntities = array_filter($settingEntities, function (EntitySettingInterface $setting): bool {
            return $setting->isSdk();
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
