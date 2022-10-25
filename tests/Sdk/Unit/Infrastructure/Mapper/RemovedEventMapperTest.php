<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Mapper\CommandMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\ConverterMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\FileMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\PlaceholderMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\RemovedEventMapper;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Mapper
 * @group RemovedEventMapperTest
 * Add your own group annotations below this line
 */
class RemovedEventMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\RemovedEventMapper
     */
    protected RemovedEventMapper $removedEventMapper;

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
        $this->removedEventMapper = new RemovedEventMapper(
            new PlaceholderMapper(),
            new CommandMapper(new ConverterMapper()),
            new FileMapper(),
        );
    }

    /**
     * @return void
     */
    public function testMapRemovedEventShouldReturnInfrastructureRemovedEvent(): void
    {
        // Arrange
        $eventData = $this->tester->createRemovedEventData();

        // Act
        $result = $this->removedEventMapper->mapRemovedEvent($eventData);

        // Assert
        $this->assertCount(count($eventData->getCommands()), $result->getCommands());
        $this->assertCount(count($eventData->getFiles()), $result->getFiles());
        $this->assertCount(count($eventData->getPlaceholders()), $result->getPlaceholders());
    }
}
