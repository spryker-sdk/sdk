<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\CliValueReceiver;

use Codeception\Test\Unit;
use RuntimeException;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\ArrayQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\StringQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactoryRegistry;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionTypeEnum;

class QuestionFactoryRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testRegistryReturnsQuestionFactoryWhenTypeExists(): void
    {
        // Arrange
        $questionFactoryRegistry = new QuestionFactoryRegistry([QuestionTypeEnum::TYPE_ARRAY => new ArrayQuestionFactory()]);

        // Act
        $questionFactor = $questionFactoryRegistry->getQuestionFactoryByType(QuestionTypeEnum::TYPE_ARRAY);

        // Assert
        $this->assertSame(QuestionTypeEnum::TYPE_ARRAY, $questionFactor::getType());
    }

    /**
     * @return void
     */
    public function testRegistryReturnsGenericQuestionFactoryWhenTypeDoesNotExist(): void
    {
        // Arrange
        $questionFactoryRegistry = new QuestionFactoryRegistry([QuestionTypeEnum::TYPE_STRING => new StringQuestionFactory()]);

        // Act
        $questionFactor = $questionFactoryRegistry->getQuestionFactoryByType('some_type');

        // Assert
        $this->assertSame(QuestionTypeEnum::TYPE_STRING, $questionFactor::getType());
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenStringTypeDoesNotExist(): void
    {
        // Arrange & Assert
        $questionFactoryRegistry = new QuestionFactoryRegistry([]);
        $this->expectException(RuntimeException::class);

        // Act
        $questionFactor = $questionFactoryRegistry->getQuestionFactoryByType('some_type');
    }
}
