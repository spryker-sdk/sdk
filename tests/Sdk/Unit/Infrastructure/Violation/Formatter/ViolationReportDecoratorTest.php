<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Violation\Formatter;

use Codeception\Test\Unit;
use CodeCompliance\Domain\Entity\PackageViolationReportInterface;
use CodeCompliance\Domain\Entity\ViolationInterface;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationDecoratorInterface;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Violation
 * @group Formatter
 * @group ViolationReportDecoratorTest
 * Add your own group annotations below this line
 */
class ViolationReportDecoratorTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationDecoratorInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    protected OutputViolationDecoratorInterface $outputViolationDecorator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->outputViolationDecorator = $this->createMock(OutputViolationDecoratorInterface::class);
    }

    /**
     * @return void
     */
    public function testDecorate(): void
    {
        // Arrange
        $violationMock = $this->createMock(ViolationInterface::class);
        $violationReportMock = $this->createMock(ViolationReportInterface::class);
        $packageViolationReportMock = $this->createMock(PackageViolationReportInterface::class);
        $packageViolationReportMock
            ->expects($this->once())
            ->method('getViolations')
            ->willReturn([$violationMock]);

        $packageViolationReportMock
            ->expects($this->once())
            ->method('getFileViolations')
            ->willReturn(['path' => [$violationMock]]);

        $violationReportMock
            ->method('getViolations')
            ->willReturn([$violationMock]);

        $violationReportMock
            ->expects($this->once())
            ->method('getPackages')
            ->willReturn([$packageViolationReportMock]);

        $this->outputViolationDecorator
            ->expects($this->exactly(3))
            ->method('decorate')
            ->willReturnArgument(0);

        $yamlViolationReportFormatter = new ViolationReportDecorator([$this->outputViolationDecorator]);

        // Act
        $yamlViolationReportFormatter->decorate($violationReportMock);
    }
}
