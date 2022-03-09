<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class ESLintTaskCest
{
    /**
     * @var string
     */
    private const COMMAND = 'validation:frontend:eslint';

    /**
     * @var string
     */
    private const REPORT = 'eslint.codestyle.json';

    /**
     * @var string
     */
    private const CONFIG_FILE = 'node_modules/@spryker/frontend-config.eslint/.eslintrc.js';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testFilesDontContainViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--file=src/ESLint/success',
            '--config=' . $I->getPathFromSdkRoot(static::CONFIG_FILE),
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('reports/'));
        Assert::assertStringNotContainsString('Violations found in files', $process->getOutput());
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testConfigNotFound(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--file=src/ESLint/success',
            '--config=notExists.js',
        ]);

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('reports/' . static::REPORT));
        Assert::assertStringContainsString('at createCLIConfigArray', $process->getOutput());
        Assert::assertEmpty(file_get_contents($I->getPathFromProjectRoot('reports/' . static::REPORT)));
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testPathNotFound(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--file=notExists',
            '--config=' . $I->getPathFromSdkRoot(static::CONFIG_FILE),
        ]);

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertStringContainsString('Please check for typing mistakes in the pattern.', $process->getOutput());
        Assert::assertFileExists($I->getPathFromProjectRoot('reports/' . static::REPORT));
        Assert::assertEmpty(file_get_contents($I->getPathFromProjectRoot('reports/' . static::REPORT)));
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testReportDirNotFound(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--file=src/ESLint/success',
            '--config=' . $I->getPathFromSdkRoot(static::CONFIG_FILE),
            '--report_dir=notExists',
        ]);

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertStringContainsString('cannot create notExists/eslint.codestyle.json: Directory nonexistent', $process->getOutput());
        Assert::assertFileDoesNotExist($I->getPathFromProjectRoot('reports/' . static::REPORT));
        Assert::assertEmpty($I->getPathFromProjectRoot('reports/' . static::REPORT));
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testFilesContainViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports();

        // Act
        $process = $I->runSdkCommand([
            static::COMMAND,
            '--file=src/ESLint/failed',
            '--config=' . $I->getPathFromSdkRoot(static::CONFIG_FILE),
        ]);

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertStringContainsString('failed.js', $process->getOutput());
        Assert::assertStringContainsString('Violations found in files', $process->getOutput());
        Assert::assertStringContainsString('Expected an assignment or function call and instead saw an expression.', $process->getOutput());
        Assert::assertFileExists($I->getPathFromProjectRoot('reports/' . static::REPORT));
    }
}
