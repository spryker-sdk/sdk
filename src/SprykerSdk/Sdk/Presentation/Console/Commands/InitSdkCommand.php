<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting as DomainSetting;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class InitSdkCommand extends AbstractInitCommand
{
    /**
     * @var string
     */
    protected const NAME = 'init:sdk';

    /**
     */
    public function __construct(
        QuestionHelper $questionHelper,
        SettingRepositoryInterface $settingRepository,
        protected CreateDatabaseDoctrineCommand $createDatabaseDoctrineCommand,
        protected MigrateCommand $doctrineMigrationCommand,
        protected Yaml $yamlParser,
        protected string $settingsPath,
    ) {
        parent::__construct(static::NAME, $questionHelper, $settingRepository);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->createDatabase($input, $output);

        $settingEntities = $this->readSettingDefinitions();
        $this->initializeSettingValues($settingEntities, $input, $output);

        return static::SUCCESS;
    }

    /**
     * @param array $setting
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Setting
     */
    protected function createSettingEntity(array $setting): DomainSetting
    {
        $settingEntity = $this->settingRepository->findOneDefinitionByPath($setting['path']);

        $settingData = [
            'path' => $setting['path'],
            'is_project' => $setting['is_project'] ?? true,
            'initialization_description' => $setting['initialization_description'] ?? null,
            'strategy' => in_array($setting['strategy'] ?? 'overwrite', ['overwrite', 'merge']) ?? 'overwrite',
            'init' => $setting['init'] ?? false,
            'values' => $setting['values']
        ];

        if ($settingEntity) {
            $settingEntity->isProject = $settingData['is_project'];
            $settingEntity->initializationDescription = $settingData['initialization_description'];
            $settingEntity->strategy = $settingData['strategy'];
            $settingEntity->hasInitialization = $settingData['init'];
            $settingEntity->values = $settingData['values'];
        } else {
            $settingEntity = new Setting(
                null,
                $settingData['path'],
                $settingData['values'],
                $settingData['strategy'],
                $settingData['is_project'],
                $settingData['init'],
                $settingData['initialization_description'],
            );
        }

        return $this->settingRepository->save($settingEntity);
    }

    /**
     * @return array
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
     * @param array $settingEntities
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return array<\SprykerSdk\Sdk\Infrastructure\Entity\Setting>
     */
    protected function initializeSettingValues(array $settingEntities, InputInterface $input, OutputInterface $output): array
    {
        $coreEntities = array_filter($settingEntities, function (Setting $setting) {
            return $setting->isProject === false;
        });
        $coreEntities = parent::initializeSettingValues($coreEntities, $input, $output);

        return array_map(function (Setting $setting): Setting {
            /** @var Setting $setting */
            $setting = $this->settingRepository->save($setting);

            return $setting;
        }, $coreEntities);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function createDatabase(InputInterface $input, OutputInterface $output): void
    {
        $this->createDatabaseDoctrineCommand->run(new ArrayInput([]), $output);
        $migrationInput = new ArrayInput([]);
        $migrationInput->setInteractive(false);
        $this->doctrineMigrationCommand->run($migrationInput, $output);
    }
}