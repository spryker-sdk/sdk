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
 * @group DeprecationsTaskCest
 * Add your own group annotations below this line
 */
class DeprecationsTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:deprecations-check';

    /**
     * @var string
     */
    protected const PROJECT_DIR = 'deprecations_project';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testDeprecationsCheckFoundDeprecations(AcceptanceTester $I): void
    {
        // Arrange
        $I->cleanReports(static::PROJECT_DIR);

        // Act
        $process = $I->runSdkCommand(
            [
                static::COMMAND,
                '--format=output',
            ],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful(), $process->getErrorOutput() ?: $process->getOutput());
        Assert::assertStringContainsString('Violations found', $process->getOutput());
    }
}
