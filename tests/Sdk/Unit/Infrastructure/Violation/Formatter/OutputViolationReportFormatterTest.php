<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Violation\Formatter;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationReportFormatter;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Violation
 * @group Formatter
 * @group OutputViolationReportFormatterTest
 * Add your own group annotations below this line
 */
class OutputViolationReportFormatterTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator&\PHPUnit\Framework\MockObject\MockObject
     */
    protected ViolationReportDecorator $violationReportDecorator;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    protected OutputInterface $output;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->violationReportDecorator = $this->createMock(ViolationReportDecorator::class);
        $this->output = $this->createMock(OutputInterface::class);
        $outputFormatter = $this->createMock(OutputFormatterInterface::class);
        $outputFormatter
            ->method('isDecorated')
            ->willReturn(false);
        $this->output
            ->method('getFormatter')
            ->willReturn($outputFormatter);
    }

    /**
     * @return void
     */
    public function testFormat(): void
    {
        // Arrange
        $violationReportMock = $this->createMock(ViolationReportInterface::class);

        $this->violationReportDecorator
            ->expects($this->once())
            ->method('decorate')
            ->willReturn($violationReportMock);

        $this->output
            ->expects($this->never())
            ->method('writeln');

        $yamlViolationReportFormatter = new OutputViolationReportFormatter($this->violationReportDecorator);
        $yamlViolationReportFormatter->setOutput($this->output);

        // Act
        $yamlViolationReportFormatter->format('name', $violationReportMock);
    }

    /**
     * @return void
     */
    public function testRead(): void
    {
        // Arrange
        $yamlViolationReportFormatter = new OutputViolationReportFormatter($this->violationReportDecorator);

        // Act
        $violationReport = $yamlViolationReportFormatter->read('name');

        // Assert
        $this->assertNull($violationReport);
    }
}
