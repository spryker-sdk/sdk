<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Workflow\TimestampedMethodMarkingStore;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Marking;

class TimestampedMethodMarkingStoreActionTest extends Unit
{
    /**
     * @return void
     */
    public function testGetMarking(): void
    {
        // Arrange
        $timestampedMethodMarkingStore = new TimestampedMethodMarkingStore(false, 'method');

        $object = new class () {
            /**
             * @return string|null
             */
            public function getMethod(): ?string
            {
                return null;
            }
        };

        // Act
        $timestampedMarking = $timestampedMethodMarkingStore->getMarking($object);

        // Assert
        $this->assertCount(0, $timestampedMarking->getPlaces());
    }

    /**
     * @return void
     */
    public function testGetMarkingIfNutEmpty(): void
    {
        // Arrange
        $timestampedMethodMarkingStore = new TimestampedMethodMarkingStore(false, 'method');

        $object = new class () {
            /**
             * @return string|null
             */
            public function getMethod(): ?string
            {
                return 'test';
            }
        };

        // Act
        $timestampedMarking = $timestampedMethodMarkingStore->getMarking($object);

        // Assert
        $this->assertCount(1, $timestampedMarking->getPlaces());
        $this->assertIsInt(1, current($timestampedMarking->getPlaces()));
        $this->assertSame('test', array_key_first($timestampedMarking->getPlaces()));
    }

    /**
     * @return void
     */
    public function testGetMarkingLogicException(): void
    {
        // Arrange
        $timestampedMethodMarkingStore = new TimestampedMethodMarkingStore(false, 'method');
        $object = new class () {
        };

        // Assert
        $this->expectException(LogicException::class);

        // Act
        $timestampedMethodMarkingStore->getMarking($object);
    }

    /**
     * @return void
     */
    public function testSetMarkingLogicException(): void
    {
        // Arrange
        $places = ['place'];
        $timestampedMethodMarkingStore = new TimestampedMethodMarkingStore(false, 'method');
        $marking = $this->createMock(Marking::class);
        $marking->expects($this->once())
            ->method('getPlaces')
            ->willReturn($places);

        $object = new class () {
        };
        $context = ['testKey'];

        // Assert
        $this->expectException(LogicException::class);

        // Act
        $timestampedMethodMarkingStore->setMarking($object, $marking, $context);
    }
}
