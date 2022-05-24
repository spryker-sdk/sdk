<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class InitSdkCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:init:sdk';

    protected CliValueReceiver $cliValueReceiver;

    protected SettingRepository $settingRepository;

    protected MigrateCommand $doctrineMigrationCommand;

    protected Yaml $yamlParser;

    protected string $settingsPath;

    protected TaskManagerInterface $taskManager;

    protected TaskRepositoryInterface $taskYamlRepository;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository $settingRepository
     * @param \Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\TaskManagerInterface $taskManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskYamlRepository
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingRepository $settingRepository,
        MigrateCommand $doctrineMigrationCommand,
        Yaml $yamlParser,
        string $settingsPath,
        TaskManagerInterface $taskManager,
        TaskRepositoryInterface $taskYamlRepository
    ) {
        $this->settingsPath = $settingsPath;
        $this->yamlParser = $yamlParser;
        $this->doctrineMigrationCommand = $doctrineMigrationCommand;
        $this->settingRepository = $settingRepository;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->taskYamlRepository = $taskYamlRepository;
        $this->taskManager = $taskManager;
        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this->createDatabase();

        $settings = $this->readSettingDefinitions();

        foreach ($settings as $setting) {
            $mode = InputOption::VALUE_REQUIRED;
            if ($setting->getStrategy() === 'merge') {
                $mode |= InputOption::VALUE_IS_ARRAY;
            }
            $this->addOption(
                $setting->getPath(),
                null,
                $mode,
                $setting->getInitializationDescription() ?? '',
            );
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initializeSettingValues($input->getOptions(), $this->readSettingDefinitions());
        $this->taskManager->initialize($this->taskYamlRepository->findAll());

        return static::SUCCESS;
    }

    /**
     * @param array $setting
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createSettingEntity(array $setting): SettingInterface
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
            $settingEntity = new Setting(
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
        $settings = $this->yamlParser->parseFile($this->settingsPath)['settings'];
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
        $coreEntities = array_filter($settingEntities, function (SettingInterface $setting): bool {
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

    /**
     * @return void
     */
    protected function createDatabase(): void
    {
        $migrationInput = new ArrayInput(['allow-no-migration']);
        $migrationInput->setInteractive(false);
        $this->doctrineMigrationCommand->run($migrationInput, new NullOutput());
    }
}
