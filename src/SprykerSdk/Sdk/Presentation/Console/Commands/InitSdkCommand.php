<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Contracts\Entity\SettingInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskInitializerInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting;
use SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class InitSdkCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'init:sdk';

    protected CliValueReceiver $cliValueReceiver;

    protected SettingRepository $settingRepository;

    protected CreateDatabaseDoctrineCommand $createDatabaseDoctrineCommand;

    protected MigrateCommand $doctrineMigrationCommand;

    protected Yaml $yamlParser;

    protected string $settingsPath;

    protected TaskInitializerInterface $taskInitializer;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\SettingRepository $settingRepository
     * @param \Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand $createDatabaseDoctrineCommand
     * @param \Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingRepository $settingRepository,
        CreateDatabaseDoctrineCommand $createDatabaseDoctrineCommand,
        MigrateCommand $doctrineMigrationCommand,
        Yaml $yamlParser,
        string $settingsPath
    ) {
        $this->settingsPath = $settingsPath;
        $this->yamlParser = $yamlParser;
        $this->doctrineMigrationCommand = $doctrineMigrationCommand;
        $this->createDatabaseDoctrineCommand = $createDatabaseDoctrineCommand;
        $this->settingRepository = $settingRepository;
        $this->cliValueReceiver = $cliValueReceiver;
        parent::__construct(static::NAME);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->createDatabase();
        $this->initializeSettingValues($this->readSettingDefinitions());
        $this->taskInitializer->initialize();

        return static::SUCCESS;
    }

    /**
     * @param array $setting
     *
     * @return \SprykerSdk\Sdk\Contracts\Entity\SettingInterface
     */
    protected function createSettingEntity(array $setting): SettingInterface
    {
        /** @var \SprykerSdk\Sdk\Infrastructure\Entity\Setting|null $settingEntity */
        $settingEntity = $this->settingRepository->findOneByPath($setting['path']);

        $settingData = [
            'path' => $setting['path'],
            'type' => $setting['type'] ?? 'string',
            'is_project' => $setting['is_project'] ?? true,
            'initialization_description' => $setting['initialization_description'] ?? null,
            'strategy' => $setting['strategy'] ?? 'overwrite',
            'init' => $setting['init'] ?? false,
            'values' => $setting['values'],
        ];

        if ($settingEntity) {
            $settingEntity->setIsProject($settingData['is_project']);
            $settingEntity->setInitializationDescription($settingData['initialization_description']);
            $settingEntity->setStrategy($settingData['strategy']);
            $settingEntity->setHasInitialization($settingData['init']);
            $settingEntity->setValues($settingData['values']);
            $settingEntity->setType($settingData['type']);
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
            );
        }

        return $this->settingRepository->save($settingEntity);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
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
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface> $settingEntities
     *
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\SettingInterface>
     */
    protected function initializeSettingValues(array $settingEntities): array
    {
        /** @var array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting> $coreEntities */
        $coreEntities = array_filter($settingEntities, function (SettingInterface $setting): bool {
            return $setting->isProject();
        });

        foreach ($coreEntities as $settingEntity) {
            if ($settingEntity->hasInitialization() === false) {
                continue;
            }

            if ($settingEntity->getValues() === null) {
                $values = $this->cliValueReceiver->receiveValue(
                    $settingEntity->getInitializationDescription() ?? 'Initial value for ' . $settingEntity->getPath(),
                    $settingEntity->getValues(),
                    $settingEntity->getType(),
                );
                $values = is_scalar($values) ?: json_encode($values);
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
        $this->createDatabaseDoctrineCommand->run(new ArrayInput([]), new NullOutput());
        $migrationInput = new ArrayInput(['allow-no-migration']);
        $migrationInput->setInteractive(false);
        $this->doctrineMigrationCommand->run($migrationInput, new NullOutput());
    }
}
