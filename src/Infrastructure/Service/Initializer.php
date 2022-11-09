<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface;
use SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface as EntitySettingInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

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
    protected TaskYamlFileLoaderInterface $taskYamlFileLoader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry
     */
    protected SettingInitializerRegistry $settingInitializerRegistry;

    /**
     * @var \Doctrine\Migrations\Tools\Console\Command\DoctrineCommand|\Doctrine\Migrations\Tools\Console\Command\MigrateCommand
     */
    protected DoctrineCommand $doctrineMigrationCommand;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $cliValueReceiver
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface $taskManager
     * @param \SprykerSdk\Sdk\Infrastructure\Loader\TaskYaml\TaskYamlFileLoaderInterface $taskYamlRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Setting\SettingInitializerRegistry $settingInitializerRegistry
     * @param \Doctrine\Migrations\Tools\Console\Command\DoctrineCommand|\Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     */
    public function __construct(
        InteractionProcessorInterface $cliValueReceiver,
        SettingRepositoryInterface $settingRepository,
        TaskManagerInterface $taskManager,
        TaskYamlFileLoaderInterface $taskYamlRepository,
        SettingInitializerRegistry $settingInitializerRegistry,
        DoctrineCommand $doctrineMigrationCommand
    ) {
        $this->settingRepository = $settingRepository;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->taskYamlFileLoader = $taskYamlRepository;
        $this->taskManager = $taskManager;
        $this->settingInitializerRegistry = $settingInitializerRegistry;
        $this->doctrineMigrationCommand = $doctrineMigrationCommand;
    }

    /**
     * @param array<string, mixed> $settings
     *
     * @return void
     */
    public function initialize(array $settings): void
    {
        $this->runMigration();

        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingDefinition */
        $settingDefinition = $this->settingRepository->initSettingDefinition();

        $this->initializeSettingValues($settings, $settingDefinition);
        $this->taskManager->initialize($this->taskYamlFileLoader->loadAll());
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

            $initializerName = $settingEntity->getInitializer();

            if ($initializerName === null || !$this->settingInitializerRegistry->hasSettingInitializer($initializerName)) {
                continue;
            }

            $this->settingInitializerRegistry->getSettingInitializer($initializerName)->initialize($settingEntity);
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

        if (!$this->isValuesShouldBeReceived($settingEntity)) {
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
     * @return bool
     */
    protected function isValuesShouldBeReceived(SettingInterface $settingEntity): bool
    {
        if ($settingEntity->isForceAskValue() && $settingEntity->isFirstInitialization()) {
            return true;
        }

        if ($settingEntity->hasInitialization() === false || $settingEntity->getValues() !== null) {
            return false;
        }

        return true;
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
                $settingEntity->getPath(),
                $settingEntity->getInitializationDescription() ?? 'Initial value for ' . $settingEntity->getPath(),
                $settingEntity->getValues(),
                $settingEntity->getType(),
                [],
            ),
        );

        return $value === null || is_scalar($value) ? $value : json_encode($value);
    }

    /**
     * @return void
     */
    protected function runMigration(): void
    {
        $migrationInput = new ArrayInput(['allow-no-migration']);
        $migrationInput->setInteractive(false);
        $this->doctrineMigrationCommand->run($migrationInput, new NullOutput());
    }
}
