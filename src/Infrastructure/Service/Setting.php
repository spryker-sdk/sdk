<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\SettingInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting as EntitySetting;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;
use Symfony\Component\Yaml\Yaml;

class Setting implements SettingInterface
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
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var string
     */
    protected string $settingsPath;

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
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface $taskManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskYamlRepository
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingRepository $settingRepository,
        Yaml $yamlParser,
        string $settingsPath,
        TaskManagerInterface $taskManager,
        TaskRepositoryInterface $taskYamlRepository
    ) {
        $this->settingRepository = $settingRepository;
        $this->yamlParser = $yamlParser;
        $this->settingsPath = $settingsPath;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->taskYamlRepository = $taskYamlRepository;
        $this->taskManager = $taskManager;
    }

    /**
     * @param array $settings
     *
     * @return void
     */
    public function initialize(array $settings): void
    {
        $this->initializeSettingValues($settings, $this->readSettingDefinitions());
        $this->taskManager->initialize($this->taskYamlRepository->findAll());
    }

    /**
     * @param array $setting
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createSettingEntity(array $setting): EntitySettingInterface
    {
        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Setting|null $settingEntity */
        $settingEntity = $this->settingRepository->findOneByPath($setting['path']);

        $settingData = $this->prepereSettingData($setting);

        if ($settingEntity) {
            $settingEntity->setIsProject($settingData['is_project']);
            $settingEntity->setInitializationDescription($settingData['initialization_description']);
            $settingEntity->setStrategy($settingData['strategy']);
            $settingEntity->setHasInitialization($settingData['init']);
            $settingEntity->setValues($settingData['values']);
            $settingEntity->setType($settingData['type']);
            $settingEntity->setInitializer($settingData['initializer']);
        } else {
            $settingEntity = new EntitySetting(
                null,
                $settingData['path'],
                $settingData['values'],
                $settingData['strategy'],
                $settingData['type'],
                $settingData['is_project'],
                $settingData['init'],
                $settingData['initialization_description'],
                $settingData['initializer'],
            );
        }

        return $this->settingRepository->save($settingEntity);
    }

    /**
     * @param array $setting
     *
     * @return array
     */
    protected function prepereSettingData(array $setting): array
    {
        return [
            'path' => $setting['path'],
            'type' => $setting['type'] ?? 'string',
            'is_project' => $setting['is_project'] ?? true,
            'initialization_description' => $setting['initialization_description'] ?? null,
            'strategy' => $setting['strategy'] ?? 'overwrite',
            'init' => $setting['init'] ?? false,
            'values' => $setting['values'],
            'initializer' => $setting['initializer'] ?? null,
        ];
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function readSettingDefinitions(): array
    {
        $settings = $this->yamlParser->parseFile($this->settingsPath)['settings'] ?? [];
        $settingEntities = [];

        foreach ($settings as $setting) {
            $settingEntities[] = $this->createSettingEntity($setting);
        }

        return $settingEntities;
    }

    /**
     * @param array $options
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settingEntities
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function initializeSettingValues(array $options, array $settingEntities): array
    {
        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting> $coreEntities */
        $coreEntities = array_filter($settingEntities, function (EntitySettingInterface $setting): bool {
            return $setting->isProject();
        });

        foreach ($coreEntities as $settingEntity) {
            if (!empty($options[$settingEntity->getPath()])) {
                $settingEntity->setValues($options[$settingEntity->getPath()]);
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
                $previousSettingValues = $settingEntity->getValues();
                $settingEntity->setValues($values);

                if ($settingEntity->isProject() === false && $values !== $previousSettingValues) {
                    $this->settingRepository->save($settingEntity);
                }
            }
        }

        return $coreEntities;
    }
}
