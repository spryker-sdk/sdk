<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Logger;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Logger\TransactionNameNewRelicProcessor;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Logger
 * @group TransactionNameNewRelicProcessorTest
 * Add your own group annotations below this line
 */
class TransactionNameNewRelicProcessorTest extends Unit
{
    /**
     * @return void
     */
    public function testInvokeShouldReturnContextWhenItNotSet(): void
    {
        // Arrange
        $record = [];
        $processor = new TransactionNameNewRelicProcessor('transaction-name');

        // Act
        $record = $processor($record);

        // Assert
        $this->assertSame(['context' => ['transaction_name' => 'transaction-name']], $record);
    }

    /**
     * @return void
     */
    public function testInvokeShouldReturnContextWhenSomeValueSetSet(): void
    {
        // Arrange
        $record = ['context' => ['foo' => 'bar']];
        $processor = new TransactionNameNewRelicProcessor('transaction-name');

        // Act
        $record = $processor($record);

        // Assert
        $this->assertSame(['context' => ['foo' => 'bar', 'transaction_name' => 'transaction-name']], $record);
    }
}
