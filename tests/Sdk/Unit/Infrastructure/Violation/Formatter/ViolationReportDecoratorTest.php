<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Violation\Formatter;

use Codeception\Test\Unit;
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
        $violationReportMock = $this->createMock(ViolationReportInterface::class);

        $this->outputViolationDecorator
            ->expects($this->never())
            ->method('decorate')
            ->willReturnArgument(0);

        $yamlViolationReportFormatter = new ViolationReportDecorator([$this->outputViolationDecorator]);

        // Act
        $yamlViolationReportFormatter->decorate($violationReportMock);
    }
}
