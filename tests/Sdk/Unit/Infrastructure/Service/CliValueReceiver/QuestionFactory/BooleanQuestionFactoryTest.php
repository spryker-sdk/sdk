<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\CliValueReceiver\QuestionFactory;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\BooleanQuestionFactory;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group CliValueReceiver
 * @group QuestionFactory
 * @group BooleanQuestionFactoryTest
 */
class BooleanQuestionFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreatesBooleanQuestion(): void
    {
        // Arrange
        $questionFactory = new BooleanQuestionFactory();

        // Act
        $question = $questionFactory->createQuestion('Some description', ['one', 'two', 'three'], 'one');

        // Assert
        $this->assertInstanceOf(ConfirmationQuestion::class, $question);
    }

    /**
     * @return void
     */
    public function testHasTypeBoolean(): void
    {
        // Act
        $type = BooleanQuestionFactory::getType();

        // Assert
        $this->assertSame($type, ValueTypeEnum::TYPE_BOOLEAN);
    }
}
