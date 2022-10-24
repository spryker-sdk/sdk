<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Adapter;

use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class Initializer
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface
     */
    protected InitializerInterface $initializer;

    /**
     * @var \Doctrine\Migrations\Tools\Console\Command\MigrateCommand
     */
    protected MigrateCommand $doctrineMigrationCommand;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InitializerInterface $initializer
     * @param \Doctrine\Migrations\Tools\Console\Command\MigrateCommand $doctrineMigrationCommand
     */
    public function __construct(InitializerInterface $initializer, MigrateCommand $doctrineMigrationCommand)
    {
        $this->initializer = $initializer;
        $this->doctrineMigrationCommand = $doctrineMigrationCommand;
    }

    /**
     * @param array $params
     *
     * @return bool is successful
     */
    public function initialize(array $params): bool
    {
        $this->runMigration();

        $criteriaDto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_HTTP, $params);

        $resultDto = $this->initializer->initialize($criteriaDto);

        return $resultDto->isSuccessful();
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
