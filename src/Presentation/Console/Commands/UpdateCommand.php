<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Commands;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\SdkVersionNotFoundException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends AbstractUpdateCommand
{
    /**
     * @var string
     */
    public const NAME = 'sdk:update:hidden-all';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface
     */
    protected LifecycleManagerInterface $lifecycleManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \Doctrine\Migrations\Tools\Console\Command\MigrateCommand
     */
    protected MigrateCommand $doctrineMigrationCommand;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface $lifecycleManager
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param \Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     */
    public function __construct(
        LifecycleManagerInterface $lifecycleManager,
        SettingRepositoryInterface $settingRepository,
        MigrateCommand $doctrineMigrationCommand
    ) {
        parent::__construct(static::NAME);
        $this->lifecycleManager = $lifecycleManager;
        $this->settingRepository = $settingRepository;
        $this->doctrineMigrationCommand = $doctrineMigrationCommand;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runMigration();

        $this->settingRepository->initSettingDefinition();

        if ($input->getOption(static::OPTION_NO_CHECK) !== null) {
            $this->checkForUpdate($output);
        }

        if ($input->getOption(static::OPTION_CHECK_ONLY) !== null) {
            $this->lifecycleManager->update();
        }

        return static::SUCCESS;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function checkForUpdate(OutputInterface $output): void
    {
        try {
            $messages = $this->lifecycleManager->checkForUpdate();
        } catch (SdkVersionNotFoundException $exception) {
            $output->writeln($exception->getMessage(), OutputInterface::VERBOSITY_VERBOSE);

            return;
        }

        foreach ($messages as $message) {
            $output->writeln($message->getMessage(), OutputInterface::VERBOSITY_VERBOSE);
        }
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
