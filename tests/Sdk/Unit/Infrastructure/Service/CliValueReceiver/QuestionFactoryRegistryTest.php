<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\CliValueReceiver;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\ArrayQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactoryRegistry;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group CliValueReceiver
 * @group QuestionFactoryRegistryTest
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
