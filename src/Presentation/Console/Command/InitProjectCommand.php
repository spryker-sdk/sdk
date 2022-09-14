<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Doctrine\DBAL\Exception\TableNotFoundException;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Service\ProjectSettingsHandler;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitProjectCommand extends Command
{
    /**
     * @var string
     */
    protected const NAME = 'sdk:init:project';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ProjectSettingsHandler
     */
    protected ProjectSettingsHandler $projectSettingsHandler;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\CliValueReceiver $cliValueReceiver
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\ProjectSettingsHandler $projectSettingsHandler
     */
    public function __construct(
        CliValueReceiver $cliValueReceiver,
        SettingRepositoryInterface $settingRepository,
        ProjectSettingsHandler $projectSettingsHandler
    ) {
        $this->settingRepository = $settingRepository;
        $this->cliValueReceiver = $cliValueReceiver;
        $this->projectSettingsHandler = $projectSettingsHandler;

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
        if ($this->projectSettingsHandler->isProjectSettingsInitialised() && !$this->isReInitializedNeeded()) {
            return static::SUCCESS;
        }

        $projectSettingsInitDto = new ProjectSettingsInitDto(
            $input->getOptions(),
            (bool)$input->getOption('default'),
        );

        $this->projectSettingsHandler->handleInitialize($projectSettingsInitDto);

        return static::SUCCESS;
    }

    /**
     * @return bool
     */
    protected function isReInitializedNeeded(): bool
    {
        return $this->cliValueReceiver->receiveValue(
            new ReceiverValue(
                'Project settings file already exists, should it be overwritten?',
                false,
                ValueTypeEnum::TYPE_BOOLEAN,
            ),
        );
    }
}
