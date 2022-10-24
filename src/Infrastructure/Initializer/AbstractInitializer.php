<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Initializer;

use SprykerSdk\Sdk\Core\Application\Dependency\ApplicableInitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto;
use SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;

abstract class AbstractInitializer implements ApplicableInitializerInterface
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
    protected TaskYamlFileLoaderInterface $taskYamlFileLoader;

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
        $this->taskYamlFileLoader = $taskYamlRepository;
        $this->taskManager = $taskManager;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeResultDto
     */
    public function initialize(InitializeCriteriaDto $criteriaDto): InitializeResultDto
    {
        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingDefinition */
        $settingDefinition = $this->settingRepository->initSettingDefinition();

        $this->initializeSettingValues($criteriaDto->getSettings(), $settingDefinition);
        $criteriaDto->setTaskCollection($this->taskYamlFileLoader->loadAll());
        $this->taskManager->initialize($criteriaDto);

        return new InitializeResultDto();
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
     * @param array $settings
     *
     * @return void
     */
    abstract protected function initializeSettingValue(EntitySettingInterface $settingEntity, array $settings): void;
}
