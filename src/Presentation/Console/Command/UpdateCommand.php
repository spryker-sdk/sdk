<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface
     */
    protected LifecycleManagerInterface $lifecycleManager;

    /**
     * @var \Doctrine\Migrations\Tools\Console\Command\MigrateCommand
     */
    protected MigrateCommand $doctrineMigrationCommand;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface
     */
    protected InitializerInterface $initializer;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\LifecycleManagerInterface $lifecycleManager
     * @param \Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface $initializer
     */
    public function __construct(
        LifecycleManagerInterface $lifecycleManager,
        MigrateCommand $doctrineMigrationCommand,
        InitializerInterface $initializer
    ) {
        parent::__construct(static::NAME);
        $this->lifecycleManager = $lifecycleManager;
        $this->doctrineMigrationCommand = $doctrineMigrationCommand;
        $this->initializer = $initializer;
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

        $criteriaDto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_CLI, []);
        $resultDto = $this->initializer->initialize($criteriaDto);

        if (!$resultDto->isSuccessful()) {
            return static::FAILURE;
        }

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
