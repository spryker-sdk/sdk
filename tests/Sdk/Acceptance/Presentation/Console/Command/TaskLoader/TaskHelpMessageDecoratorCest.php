<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Acceptance\Presentation\Console\Command\TaskLoader;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * Auto-generated group annotations
 *
 * @group Acceptance
 * @group Presentation
 * @group Console
 * @group Command
 * @group TaskLoader
 * @group TaskHelpMessageDecoratorCest
 * Add your own group annotations below this line
 */
class TaskHelpMessageDecoratorCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'validation:php:all';

    /**
     * @var string
     */
    protected const PROJECT_DIR = 'project';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testValidateTaskSuccessful(AcceptanceTester $I): void
    {
        // Act
        $process = $I->runSdkCommand(
            [static::COMMAND, '--help'],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertStringContainsString("Task set sub-tasks:\n   - validation:php", $process->getOutput());
    }
}
