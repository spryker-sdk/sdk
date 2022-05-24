<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;
use SprykerSdk\Sdk\Infrastructure\Mapper\LifecycleMapper;
use SprykerSdk\Sdk\Infrastructure\Mapper\RemovedEventMapperInterface;
use SprykerSdk\Sdk\Tests\UnitTester;

class LifecycleMapperTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\LifecycleMapper
     */
    protected LifecycleMapper $lifecycleMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\RemovedEventMapperInterface
     */
    protected RemovedEventMapperInterface $removedEventMapper;

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
        $this->removedEventMapper = $this->createMock(RemovedEventMapperInterface::class);
        $this->lifecycleMapper = new LifecycleMapper($this->removedEventMapper);
    }

    /**
     * @return void
     */
    public function testMapLifecycleShouldReturnInfrastructureLifecycle(): void
    {
        // Arrange
        $lifecycle = $this->tester->createLifecycle();
        $removedEvent = new RemovedEvent();

        $this->removedEventMapper
            ->expects($this->once())
            ->method('mapRemovedEvent')
            ->willReturn($removedEvent);

        // Act
        $result = $this->lifecycleMapper->mapLifecycle($lifecycle);

        // Assert
        $this->assertSame($removedEvent, $result->getRemovedEventData());
    }
}
