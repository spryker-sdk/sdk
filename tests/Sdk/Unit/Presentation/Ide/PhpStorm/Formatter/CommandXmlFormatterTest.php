<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Ide\PhpStorm\Formatter;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatter;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Presentation
 * @group Ide
 * @group PhpStorm
 * @group Formatter
 * @group CommandXmlFormatterTest
 * Add your own group annotations below this line
 */
class CommandXmlFormatterTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatter
     */
    protected CommandXmlFormatter $commandXmlFormatter;

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
        $this->commandXmlFormatter = new CommandXmlFormatter();
    }

    /**
     * @return void
     */
    public function testFormat(): void
    {
        // Arrange
        /** @var array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Param> $params */
        $params = [
            $this->tester->createPhpStormParam('param1', [1, 2, 3]),
            $this->tester->createPhpStormParam('param2', 'string'),
        ];

        /** @var array<\SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Dto\Option> $options */
        $options = [
            $this->tester->createPhpStormOption('option1', 'o1'),
            $this->tester->createPhpStormOption('option2', 'o2'),
        ];

        $command = $this->tester->createPhpStormCommand('command:name', $params, $options, 'Help!');

        // Act
        $result = $this->commandXmlFormatter->format($command);

        // Assert
        $this->assertNotEmpty($result);
        $this->assertSame($command->getName(), $result['name']);
        $this->assertSame($command->getHelp(), $result['help']);
        $this->assertSame($params[0]->getName(), $result['params'][0]['name']);
        $this->assertSame($params[0]->getDefaultValue(), $result['params'][0]['default']);
        $this->assertSame($params[1]->getName(), $result['params'][1]['name']);
        $this->assertSame($params[1]->getDefaultValue(), $result['params'][1]['default']);
        $this->assertSame($options[0]->getName(), $result['optionsBefore']['option'][0]['@name']);
        $this->assertSame($options[0]->getShortcut(), $result['optionsBefore']['option'][0]['@shortcut']);
        $this->assertSame($options[0]->getHelp(), $result['optionsBefore']['option'][0]['help']);
        $this->assertSame($options[1]->getName(), $result['optionsBefore']['option'][1]['@name']);
        $this->assertSame($options[1]->getShortcut(), $result['optionsBefore']['option'][1]['@shortcut']);
        $this->assertSame($options[1]->getHelp(), $result['optionsBefore']['option'][1]['help']);
    }
}
