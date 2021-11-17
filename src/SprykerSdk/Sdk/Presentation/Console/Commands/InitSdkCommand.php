<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Core\Domain\Entity\Setting as DomainSetting;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Setting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

class InitSdkCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'init:sdk';

    /**
     */
    public function __construct(
        protected QuestionHelper $questionHelper,
        protected SettingRepositoryInterface $settingRepository,
        protected CreateDatabaseDoctrineCommand $createDatabaseDoctrineCommand,
        protected MigrateCommand $doctrineMigrationCommand,
        protected Yaml $yamlParser,
        protected string $settingsPath,
    ) {
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
            'type' => $setting['type'] ?? 'string',
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
            $settingEntity->type = $settingData['type'];
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

        foreach ($coreEntities as $settingEntity) {
            if ($settingEntity->hasInitialization === false) {
                continue;
            }

            if ($settingEntity->values === null) {
                $values = $this->questionHelper->ask(
                    $input,
                    $output,
                    new Question($settingEntity->initializationDescription ?? 'Initial value for ' . $settingEntity->path)
                );
                $values = is_scalar($values) ?? json_decode($values);
                $previousSettingValues = $settingEntity->values;
                $settingEntity->values = $values;

                if ($settingEntity->isProject === false && $values !== $previousSettingValues) {
                    $this->settingRepository->save($settingEntity);
                }
            }
        }

        return $coreEntities;
    }

    /**
     * @throws \Exception
     */
    protected function createDatabase(): void
    {
        $this->createDatabaseDoctrineCommand->run(new ArrayInput([]), new NullOutput());
        $migrationInput = new ArrayInput([]);
        $migrationInput->setInteractive(false);
        $this->doctrineMigrationCommand->run($migrationInput, new NullOutput());
    }
}