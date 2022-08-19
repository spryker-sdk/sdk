<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Task;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

class CodeSnifferTaskSetCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:codestyle';

    /**
     * @skip Test doesn't work due to path conflicts set in the phpcs.xml
     *
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
            '--format=yaml',
        ]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('.ssdk/reports/' . static::COMMAND . '.violations.yaml'));
    }

    /**
     * @skip Test doesn't work due to path conflicts set in the phpcs.xml
     *
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
            '--format=yaml',
        ]);

        // Assert
        Assert::assertFalse($process->isSuccessful());
        Assert::assertFileExists($I->getPathFromProjectRoot('.ssdk/reports/' . static::COMMAND . '.violations.yaml'));
    }
}
