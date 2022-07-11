<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;

class Initializer implements InitializerInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository
     */
    protected SettingRepository $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskYamlRepository;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository $settingRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface $taskManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskYamlRepository
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingRepository $settingRepository,
        TaskManagerInterface $taskManager,
        TaskRepositoryInterface $taskYamlRepository
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
        $this->initializeSettingValues($settings, $this->settingRepository->getSettingDefinition());
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
            return !$setting->isProject();
        });

        foreach ($coreEntities as $settingEntity) {
            if (!empty($settings[$settingEntity->getPath()])) {
                $settingEntity->setValues($settings[$settingEntity->getPath()]);
                $this->settingRepository->save($settingEntity);

                continue;
            }

            if ($settingEntity->hasInitialization() === false) {
                continue;
            }

            if ($settingEntity->getValues() === null) {
                $values = $this->cliValueReceiver->receiveValue(
                    new ReceiverValue(
                        $settingEntity->getInitializationDescription() ?? 'Initial value for ' . $settingEntity->getPath(),
                        $settingEntity->getValues(),
                        $settingEntity->getType(),
                    ),
                );
                $values = is_scalar($values) ? $values : json_encode($values);
                $settingEntity->setValues($values);

                if ($values !== $settingEntity->getValues()) {
                    $this->settingRepository->save($settingEntity);
                }
            }
        }

        return $coreEntities;
    }
}
