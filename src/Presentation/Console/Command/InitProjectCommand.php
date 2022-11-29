<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Doctrine\DBAL\Exception\TableNotFoundException;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitProjectCommand extends Command
{
    /**
     * @var string
     */
    public const NAME = 'sdk:init:project';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializerInterface
     */
    protected ProjectSettingsInitializerInterface $projectSettingsInitializer;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $cliValueReceiver
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Initializer\ProjectSettingsInitializer $projectSettingsInitializer
     */
    public function __construct(
        InteractionProcessorInterface $cliValueReceiver,
        SettingRepositoryInterface $settingRepository,
        ProjectSettingsInitializerInterface $projectSettingsInitializer
    ) {
        $this->settingRepository = $settingRepository;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->projectSettingsInitializer = $projectSettingsInitializer;

        parent::__construct(static::NAME);
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption(
            'default',
            'd',
            InputOption::VALUE_NONE,
            'Use predefined settings without approve',
        );
        try {
            $settings = $this->settingRepository->findProjectSettings();
        } catch (TableNotFoundException $e) {
            $this->setHidden(true);

            return;
        }

        foreach ($settings as $setting) {
            if (!$setting->hasInitialization()) {
                continue;
            }

            $mode = InputOption::VALUE_REQUIRED;
            if ($setting->getStrategy() === 'merge') {
                $mode = $mode | InputOption::VALUE_IS_ARRAY;
            }
            $this->addOption(
                $setting->getPath(),
                null,
                $mode,
                (string)$setting->getInitializationDescription(),
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
        if ($this->projectSettingsInitializer->isProjectSettingsInitialised() && !$this->isReInitializedNeeded()) {
            return static::SUCCESS;
        }

        $projectSettingsInitDto = new ProjectSettingsInitDto(
            $input->getOptions(),
            (bool)$input->getOption('default'),
        );

        $this->projectSettingsInitializer->initialize($projectSettingsInitDto);

        return static::SUCCESS;
    }

    /**
     * @return bool
     */
    protected function isReInitializedNeeded(): bool
    {
        return $this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                'overwrite-setting',
                'Project settings file already exists, should it be overwritten?',
                false,
                ValueTypeEnum::TYPE_BOOL,
            ),
        );
    }
}
