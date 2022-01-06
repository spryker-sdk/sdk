<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger;
use SprykerSdk\Sdk\Tests\UnitTester;

class ViolationReportMergerTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger
     */
    protected ViolationReportMerger $violationReportMerger;

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

        $this->violationReportMerger = new ViolationReportMerger();
    }

    /**
     * @return void
     */
    public function testMergeWithEmptyViolationReportsShouldReturnEmptyViolationReport(): void
    {
        // Arrange
        $reports = [];

        // Act
        $result = $this->violationReportMerger->merge($reports);

        // Assert
        $this->assertEmpty($result->getPath());
        $this->assertEmpty($result->getProject());
        $this->assertEmpty($result->getViolations());
        $this->assertEmpty($result->getViolations());
    }

    /**
     * @return void
     */
    public function testMergeWithViolationReportsShouldReturnMergedViolationReport(): void
    {
        // Arrange
        $violationReport = $this->tester->createViolationReport($this->tester->createArrayViolationReport());
        $reports = [
            $violationReport,
        ];

        // Act
        $result = $this->violationReportMerger->merge($reports);

        // Assert
        foreach ($reports as $report) {
            $this->assertSame($result->getViolations(), $report->getViolations());
        }

        $this->assertSame($result->getProject(), $violationReport->getProject());
        $this->assertSame($result->getPath(), $violationReport->getPath());
    }
}
