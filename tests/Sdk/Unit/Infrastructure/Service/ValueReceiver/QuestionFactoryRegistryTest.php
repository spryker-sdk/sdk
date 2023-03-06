<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service\ValueReceiver;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\ArrayQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactoryRegistry;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group ValueReceiver
 * @group QuestionFactoryRegistryTest
 * Add your own group annotations below this line
 */
class QuestionFactoryRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testRegistryReturnsQuestionFactoryWhenTypeExists(): void
    {
        // Arrange
        $questionFactoryRegistry = new QuestionFactoryRegistry([ValueTypeEnum::TYPE_ARRAY => new ArrayQuestionFactory()]);

        // Act
        $questionFactory = $questionFactoryRegistry->getQuestionFactoryByType(ValueTypeEnum::TYPE_ARRAY);

        // Assert
        $this->assertSame(ValueTypeEnum::TYPE_ARRAY, $questionFactory::getType());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenStringTypeDoesNotExist(): void
    {
        // Arrange & Assert
        $questionFactoryRegistry = new QuestionFactoryRegistry([]);
        $this->expectException(InvalidArgumentException::class);

        // Act
        $questionFactoryRegistry->getQuestionFactoryByType('some_type');
    }
}
