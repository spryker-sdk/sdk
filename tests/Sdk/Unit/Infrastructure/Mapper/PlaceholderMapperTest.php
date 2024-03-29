<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Mapper\PlaceholderMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Mapper
 * @group PlaceholderMapperTest
 * Add your own group annotations below this line
 */
class PlaceholderMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\PlaceholderMapper
     */
    protected PlaceholderMapper $placeholderMapper;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->placeholderMapper = new PlaceholderMapper();
    }

    /**
     * @return void
     */
    public function testMapPlaceholderShouldReturnInfrastructurePlaceholder(): void
    {
        // Arrange
        $placeholder = $this->tester->createPlaceholder('placeholder', 'STATIC', true);

        // Act
        $result = $this->placeholderMapper->mapPlaceholder($placeholder);

        // Assert
        $this->assertSame($placeholder->getConfiguration(), $result->getConfiguration());
        $this->assertSame($placeholder->getName(), $result->getName());
        $this->assertSame($placeholder->getValueResolver(), $result->getValueResolver());
    }
}
