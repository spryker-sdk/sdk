<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Appplication\Service\SettingManager;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitProjectCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'init:project';

    protected CliValueReceiver $cliValueReceiver;

    protected SettingManager $projectSettingManager;

    protected SettingRepositoryInterface $settingRepository;

    protected string $projectSettingFileName;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\SettingManager $projectSettingManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param string $projectSettingFileName
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingManager $projectSettingManager,
        SettingRepositoryInterface $settingRepository,
        string $projectSettingFileName
    ) {
        $this->projectSettingFileName = $projectSettingFileName;
        $this->settingRepository = $settingRepository;
        $this->projectSettingManager = $projectSettingManager;
        $this->cliValueReceiver = $cliValueReceiver;
        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addOption(
            'default',
            'd',
            InputOption::VALUE_NONE | InputOption::VALUE_REQUIRED,
            'Use predefined settings without approve',
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        $projectSettingPath = $this->projectSettingFileName;

        if (file_exists($projectSettingPath)) {
            if (
                !$this->cliValueReceiver->receiveValue(
                    new ReceiverValue('.ssdk file already exists, should it be overwritten? [n]', false, 'boolean'),
                )
            ) {
                return static::SUCCESS;
            }
        }

        $settingEntities = $this->settingRepository->findProjectSettings();
        $needsToAsk = $input->getOption('default');
        $settingEntities = $this->initializeSettingValues($settingEntities, $needsToAsk);
        $this->writeProjectSettings($settingEntities);

        return static::SUCCESS;
    }

    /**
     * @param array<string, \SprykerSdk\SdkContracts\Entity\SettingInterface> $settingEntities
     * @param bool $needsToAsk
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    protected function initializeSettingValues(array $settingEntities, bool $needsToAsk): array
    {
        foreach ($settingEntities as $settingEntity) {
            if ($settingEntity->hasInitialization() === false) {
                continue;
            }
            $values = $settingEntity->getValues();

            if (!$needsToAsk) {
                $questionDescription = $settingEntity->getInitializationDescription();

                if (empty($questionDescription)) {
                    $questionDescription = 'Initial value for ' . $settingEntity->getPath();
                }

                $values = $this->cliValueReceiver->receiveValue(
                    new ReceiverValue(
                        $questionDescription,
                        $settingEntity->getValues(),
                        $settingEntity->getType(),
                    ),
                );
            }

            $values = match ($settingEntity->getType()) {
                'boolean' => (bool)$values,
                'array' => (array)$values,
                default => (string)$values,
            };

            if ($settingEntity->getStrategy() === SettingInterface::STRATEGY_MERGE) {
                $values = array_merge((array)$settingEntity->getValues(), (array)$values);
            }

            $settingEntity->setValues($values);
        }

        return $settingEntities;
    }

    /**
     * @param array<int, \SprykerSdk\SdkContracts\Entity\SettingInterface> $projectSettings
     *
     * @return void
     */
    protected function writeProjectSettings(array $projectSettings): void
    {
        $projectValues = [];

        foreach ($projectSettings as $projectSetting) {
            $projectValues[$projectSetting->getPath()] = $projectSetting->getValues();
        }

        $this->projectSettingManager->setSettings($projectValues);
    }
}
