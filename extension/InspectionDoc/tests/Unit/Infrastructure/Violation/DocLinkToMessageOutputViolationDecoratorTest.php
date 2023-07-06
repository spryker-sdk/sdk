<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Tests\Unit\Infrastructure\Violation;

use Codeception\Test\Unit;
use InspectionDoc\Entity\InspectionDocInterface;
use InspectionDoc\Infrastructure\Reader\InspectionDocReaderInterface;
use InspectionDoc\Infrastructure\Violation\DocLinkToMessageOutputViolationDecorator;
use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;

/**
 * Auto-generated group annotations
 *
 * @group Tests
 * @group Unit
 * @group Infrastructure
 * @group Violation
 * @group DocLinkToMessageOutputViolationDecoratorTest
 * Add your own group annotations below this line
 */
class DocLinkToMessageOutputViolationDecoratorTest extends Unit
{
    /**
     * @return void
     */
    public function testDecorator(): void
    {
        // Arrange
        $inspectionDocInterface = $this->createMock(InspectionDocInterface::class);
        $inspectionDocInterface->expects($this->once())
            ->method('getLink')
            ->willReturn('testLink');

        $inspectionDocReader = $this->createMock(InspectionDocReaderInterface::class);
        $inspectionDocReader->expects($this->once())
            ->method('findByErrorCode')
            ->with('test')
            ->willReturn($inspectionDocInterface);

        $docLinkToMessageOutputViolationDecorator = new DocLinkToMessageOutputViolationDecorator(
            $inspectionDocReader,
            'test',
        );
        $violation = $this->createMock(ViolationInterface::class);
        $violation->expects($this->exactly(2))
            ->method('getAdditionalAttributes')
            ->willReturn(['inspectionId' => 'test']);

        // Act
        $violationData = $docLinkToMessageOutputViolationDecorator->decorate($violation);

        // Assert
        $this->assertSame("\nMore information: testtestLink", $violationData->getMessage());
    }

    /**
     * @return void
     */
    public function testWithoutDecorator(): void
    {
        // Arrange
        $inspectionDocInterface = $this->createMock(InspectionDocInterface::class);
        $inspectionDocInterface->expects($this->never())
            ->method('getLink')
            ->willReturn('testLink');

        $inspectionDocReader = $this->createMock(InspectionDocReaderInterface::class);
        $inspectionDocReader->expects($this->never())
            ->method('findByErrorCode')
            ->with('test')
            ->willReturn($inspectionDocInterface);

        $docLinkToMessageOutputViolationDecorator = new DocLinkToMessageOutputViolationDecorator(
            $inspectionDocReader,
            'test',
        );
        $violation = $this->createMock(ViolationInterface::class);
        $violation->expects($this->exactly(2))
            ->method('getAdditionalAttributes')
            ->willReturn([]);

        // Act
        $violationData = $docLinkToMessageOutputViolationDecorator->decorate($violation);

        // Assert
        $this->assertSame('', $violationData->getMessage());
    }
}
