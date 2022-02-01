<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\Violation;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;
use SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group Violation
 * @group ViolationReportMergerTest
 */
class ViolationReportMergerTest extends Unit
{
    /**
     * @return void
     */
    public function testMerge(): void
    {
        // Arrange
        $violationReportMerger = new ViolationReportMerger();

        // Act
        $violationReport = $violationReportMerger->merge([
            $this->createViolationReport('first'),
            $this->createViolationReport('second'),
        ]);

        // Assert
        $this->assertCount(2, $violationReport->getViolations());
        $this->assertCount(2, $violationReport->getPackages());
        foreach ($violationReport->getPackages() as $package) {
            $this->assertCount(1, $package->getViolations());
            $this->assertCount(2, $package->getFileViolations());
        }
    }

    /**
     * @param string $postfix
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    protected function createViolationReport(string $postfix = ''): ViolationReportInterface
    {
        return new ViolationReport(
            'project' . $postfix,
            'path' . $postfix,
            [$this->createViolationReportConverter($postfix)],
            [$this->createPackageViolationReport($postfix)],
        );
    }

    /**
     * @param string $postfix
     *
     * @return \SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface
     */
    protected function createPackageViolationReport(string $postfix = ''): PackageViolationReportInterface
    {
        return new PackageViolationReport(
            'package' . $postfix,
            'path' . $postfix,
            [$this->createViolationReportConverter($postfix)],
            [
                'file' => [$this->createViolationReportConverter($postfix)],
                'file' . $postfix => [$this->createViolationReportConverter($postfix)],
            ],
        );
    }

    /**
     * @param string $postfix
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationInterface
     */
    protected function createViolationReportConverter(string $postfix = ''): ViolationInterface
    {
        return new Violation(
            'id' . $postfix,
            'message' . $postfix,
        );
    }
}
