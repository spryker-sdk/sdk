<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Acceptance\Presentation\Console\Command\Validator;

use PHPUnit\Framework\Assert;
use SprykerSdk\Sdk\Tests\AcceptanceTester;

/**
 * Auto-generated group annotations
 *
 * @group Acceptance
 * @group Presentation
 * @group Console
 * @group Command
 * @group Validator
 * @group ValidateTaskCommandCest
 * Add your own group annotations below this line
 */
class ValidateTaskCommandCest
{
    /**
     * @var string
     */
    protected const COMMAND = 'sdk:validate:task';

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
            [static::COMMAND],
            $I->getProjectRoot(static::PROJECT_DIR),
        );

        // Assert
        Assert::assertTrue($process->isSuccessful());
    }
}
