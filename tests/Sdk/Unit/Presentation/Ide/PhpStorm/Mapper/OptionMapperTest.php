<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Ide\PhpStorm\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\OptionMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

class OptionMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\OptionMapper
     */
    protected OptionMapper $optionMapper;

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
        $this->optionMapper = new OptionMapper();
    }

    /**
     * @return void
     */
    public function testMapToIdeOption(): void
    {
        // Arrange
        $option = $this->tester->createSymfonyInputOption('option', 'o', 'An option helps you!');

        // Act
        $result = $this->optionMapper->mapToIdeOption($option);

        // Assert
        $this->assertSame($option->getDescription(), $result->getHelp());
        $this->assertSame($option->getName(), $result->getName());
        $this->assertSame($option->getShortcut(), $result->getShortcut());
    }
}
