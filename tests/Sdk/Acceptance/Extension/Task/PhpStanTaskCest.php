<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Task;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class PhpStanTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:static';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testPhpStanRunsSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/PhpStan/success',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testPhpStanFindingViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/PhpStan/fail',
        ]);

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertNotEmpty($process->getOutput());
        Assert::assertStringContainsString('Violations found in files', $process->getOutput());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testPhpStanGeneratingFileReport(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $I->runSdkCommand([
            static::COMMAND,
            '--path=src/PhpStan/fail',
            '--format=yaml',
        ]);

        // Assert
        Assert::assertFileExists($I->getPathFromProjectRoot('.ssdk/reports/' . static::COMMAND . '.violations.yaml'));
    }
}
