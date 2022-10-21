<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Command;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
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
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface
     */
    protected InitializerInterface $initializerService;

    /**
     * @var \Doctrine\Migrations\Tools\Console\Command\MigrateCommand
     */
    protected MigrateCommand $doctrineMigrationCommand;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface $initializerService
     * @param \Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param string $settingsPath
     */
    public function __construct(
        InitializerInterface $initializerService,
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

        $criteriaDto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_CLI, $input->getOptions());

        $resultDto = $this->initializerService->initialize($criteriaDto);

        return $resultDto->isSuccessful() ? static::SUCCESS : static::FAILURE;
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
