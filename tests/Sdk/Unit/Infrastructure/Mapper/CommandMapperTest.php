<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Infrastructure\Mapper\CommandMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Mapper
 * @group CommandMapperTest
 * Add your own group annotations below this line
 */
class CommandMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\CommandMapper
     */
    protected CommandMapper $commandMapper;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->commandMapper = new CommandMapper(new ConverterMapper());
    }

    /**
     * @return void
     */
    public function testMapCommandShouldReturnInfrastructureCommand(): void
    {
        // Arrange
        $converter = new Converter('converter', []);
        $command = $this->tester->createCommand($converter);

        // Act
        $result = $this->commandMapper->mapCommand($command);

        // Assert
        $this->assertSame($command->getCommand(), $result->getCommand());
        $this->assertSame($command->getType(), $result->getType());
        $this->assertSame($command->getTags(), $result->getTags());
        $this->assertSame($command->getConverter()->getName(), $result->getConverter()->getName());
        $this->assertSame($command->getConverter()->getConfiguration(), $result->getConverter()->getConfiguration());
        $this->assertSame($command->getErrorMessage(), $result->getErrorMessage());
    }
}
