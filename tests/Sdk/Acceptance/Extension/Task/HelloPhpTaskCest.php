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
 * @group HelloPhpTaskCest
 * Add your own group annotations below this line
 */
class HelloPhpTaskCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'hello:php';

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function _before(AcceptanceTester $I): void
    {
        $I->skipCliInteractiveTest();
    }

    /**
     * @param \SprykerSdk\Sdk\Tests\AcceptanceTester $I
     *
     * @return void
     */
    public function testHelloPhpRunSuccessfully(AcceptanceTester $I): void
    {
        // Arrange
        $process = $I->runSdkCommand([static::COMMAND]);

        // Assert
        Assert::assertTrue($process->isSuccessful());
        Assert::assertStringContainsString('Success: Hello PHP', $process->getOutput());
    }
}
