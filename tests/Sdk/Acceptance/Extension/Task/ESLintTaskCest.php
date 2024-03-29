<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Task;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Acceptance
 * @group Extension
 * @group Task
 * @group ESLintTaskCest
 * Add your own group annotations below this line
 */
class ESLintTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:frontend:eslint';

    /**
     * @var string
     */
    protected const PROJECT_DIR = 'eslint';

    /**
     * @var string
     */
    protected const CONFIG_FILE = 'node_modules/@spryker/frontend-config.eslint/.eslintrc.js';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testFilesDontContainViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--file=src/success',
                '--config=' . $I->getPathFromSdkRoot(static::CONFIG_FILE),
                '--format=yaml',
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertFileExists(
            $I->getPathFromProjectRoot('.ssdk/reports/' . static::COMMAND . '.violations.yaml', static::PROJECT_DIR),
        );
    }

    /**
     * @group testConfigNotFound
     *
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testConfigNotFound(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--file=src/success',
                '--config=notExists.js',
                '--format=yaml',
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertFileDoesNotExist($I->getPathFromProjectRoot('.ssdk/reports/' . static::COMMAND . '.violations.yaml', static::PROJECT_DIR));
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testPathNotFound(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--file=notExists',
                '--config=' . $I->getPathFromSdkRoot(static::CONFIG_FILE),
                '--format=yaml',
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertFileDoesNotExist($I->getPathFromProjectRoot('.ssdk/reports/' . static::COMMAND . '.violations.yaml', static::PROJECT_DIR));
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testFilesContainViolations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--file=src/failed',
                '--config=' . $I->getPathFromSdkRoot(static::CONFIG_FILE),
                '--format=yaml',
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('.ssdk/reports/' . static::COMMAND . '.violations.yaml', static::PROJECT_DIR));
    }
}
