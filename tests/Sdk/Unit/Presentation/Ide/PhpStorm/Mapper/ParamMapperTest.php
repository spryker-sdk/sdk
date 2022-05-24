<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Ide\PhpStorm\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\ParamMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

class ParamMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\ParamMapper
     */
    protected ParamMapper $paramMapper;

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
        $this->paramMapper = new ParamMapper();
    }

    /**
     * @return void
     */
    public function testMapToIdeParam(): void
    {
        // Arrange
        $argument = $this->tester->createSymfonyInputArgument('numbers', [1, 2, 3]);

        // Act
        $result = $this->paramMapper->mapToIdeParam($argument);

        // Assert
        $this->assertSame($argument->getName(), $result->getName());
        $this->assertSame($argument->getDefault(), $result->getDefaultValue());
    }
}
