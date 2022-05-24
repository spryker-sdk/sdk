<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

class ConverterMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapper
     */
    protected ConverterMapper $converterMapper;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->converterMapper = new ConverterMapper();
    }

    /**
     * @return void
     */
    public function testMapConverterShouldReturnInfrastructureConverter(): void
    {
        // Arrange
        $converter = new Converter('converter', []);

        // Act
        $result = $this->converterMapper->mapConverter($converter);

        // Assert
        $this->assertSame($converter->getName(), $result->getName());
        $this->assertSame($converter->getConfiguration(), $result->getConfiguration());
    }
}
