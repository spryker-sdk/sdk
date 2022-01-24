<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Dto;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Dto
 * @group ReceiverValueTest
 */
class ReceiverValueTest extends Unit
{
    /**
     * @return void
     */
    public function testReceivedValueGettersShouldReturnCorrectValues(): void
    {
        // Arrange
        $description = 'Test description';
        $defaultValue = 'test';
        $type = 'string';
        $choices = [
            '1',
            '2',
        ];

        $receiverValue = new ReceiverValue(
            $description,
            $defaultValue,
            $type,
            $choices,
        );

        // Assert
        $this->assertSame($description, $receiverValue->getDescription());
        $this->assertSame($defaultValue, $receiverValue->getDefaultValue());
        $this->assertSame($type, $receiverValue->getType());
        $this->assertSame($choices, $receiverValue->getChoiceValues());
    }
}
