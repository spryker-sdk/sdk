<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Infrastructure\Service\Initializer;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class InitCommand extends AbstractInitCommand
{
    /**
     * @var string
     */
    public const NAME = 'sdk:init:hidden-sdk';

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Initializer
     */
    protected Initializer $initializerService;

    /**
     * @var \Doctrine\Migrations\Tools\Console\Command\MigrateCommand
     */
    protected MigrateCommand $doctrineMigrationCommand;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Initializer $initializerService
     * @param \Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     */
    public function __construct(
        Initializer $initializerService,
        MigrateCommand $doctrineMigrationCommand,
        Yaml $yamlParser,
        string $settingsPath
    ) {
        $this->initializerService = $initializerService;
        $this->doctrineMigrationCommand = $doctrineMigrationCommand;
        parent::__construct($yamlParser, $settingsPath, static::NAME);

        $this->setHidden(true);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runMigration();

        $this->initializerService->initialize($input->getOptions());

        return static::SUCCESS;
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
