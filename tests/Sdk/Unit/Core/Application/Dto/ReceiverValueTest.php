<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Dto;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Dto
 * @group ReceiverValueTest
 * Add your own group annotations below this line
 */
class ReceiverValueTest extends Unit
{
    /**
     * @return void
     */
    public function testReceivedValueGettersShouldReturnCorrectValues(): void
    {
        // Arrange
        $alias = 'string';
        $description = 'Test description';
        $defaultValue = 'test';
        $type = 'string';
        $choices = [
            '1',
            '2',
        ];

        $receiverValue = new ReceiverValue(
            $alias,
            $description,
            $defaultValue,
            $type,
            $choices,
        );

        // Assert
        $this->assertSame($alias, $receiverValue->getAlias());
        $this->assertSame($description, $receiverValue->getDescription());
        $this->assertSame($defaultValue, $receiverValue->getDefaultValue());
        $this->assertSame($type, $receiverValue->getType());
        $this->assertSame($choices, $receiverValue->getChoiceValues());
    }
}
