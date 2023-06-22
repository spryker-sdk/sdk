<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Logger;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Logger\NewRelicFormatter;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Logger
 * @group NewRelicFormatterTest
 * Add your own group annotations below this line
 */
class NewRelicFormatterTest extends Unit
{
    /**
     * @return void
     */
    public function testFormatShouldAddValuesInRecord(): void
    {
        // Arrange
        $workspace = 'paas-demo';
        $ciExecutionId = 'ci-execution-id';
        $record = [];

        $formatter = new NewRelicFormatter($workspace, $ciExecutionId);

        // Act
        $record = $formatter->format($record);

        // Assert
        $this->assertSame(['context' => ['workspace_name' => 'paas-demo', 'ci_execution_id' => 'ci-execution-id']], $record);
    }
}
