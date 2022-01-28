<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Presentation\Ide\PhpStorm\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\CommandMapper;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\OptionMapper;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\ParamMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

class CommandMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\CommandMapper
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
        $this->commandMapper = new CommandMapper(new OptionMapper(), new ParamMapper());
    }

    /**
     * @return void
     */
    public function testMapToIdeCommand(): void
    {
        // Arrange
        $command = $this->tester->createSymfonyCommand('command:name', 'Help for you');

        // Act
        $result = $this->commandMapper->mapToIdeCommand($command);

        // Assert
        $this->assertSame($command->getName(), $result->getName());
        $this->assertSame($command->getHelp(), $result->getHelp());
        $this->assertCount(count($command->getDefinition()->getArguments()), $result->getParams());
        $this->assertCount(count($command->getDefinition()->getOptions()), $result->getOptionsBefore());
    }
}
