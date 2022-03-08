<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class CodeSnifferTaskSetCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:codestyle';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testNoViolationsFound(AcceptanceTester $I)
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/CodeSniffer/success',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('reports/phpcs.codestyle.json'));
        Assert::assertStringNotContainsString('Violation', $process->getOutput());
        Assert::assertStringContainsString(
            '"totals":{"errors":0,"warnings":0,"fixable":0}',
            file_get_contents($I->getPathFromProjectRoot('reports/phpcs.codestyle.json')),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testViolationsFound(AcceptanceTester $I)
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--path=src/CodeSniffer/fail',
        ]);

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('reports/phpcs.codestyle.json'));
        Assert::assertStringContainsString('Violation', $process->getOutput());
        Assert::assertStringContainsString('Class name "Success" doesn\'t match filename, expected "Fail"', $process->getOutput());
        Assert::assertStringContainsString(
            '"totals":{"errors":1,"warnings":0,"fixable":0}',
            file_get_contents($I->getPathFromProjectRoot('reports/phpcs.codestyle.json')),
        );
    }
}
