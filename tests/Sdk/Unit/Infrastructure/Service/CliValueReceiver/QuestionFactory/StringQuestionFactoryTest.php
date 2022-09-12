<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\CliValueReceiver\QuestionFactory;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\StringQuestionFactory;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group CliValueReceiver
 * @group QuestionFactory
 * @group GenericQuestionFactoryTest
 */
class StringQuestionFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreatesChoiceQuestionWhenHaveChoices(): void
    {
        // Arrange
        $questionFactory = new StringQuestionFactory();

        // Act
        $question = $questionFactory->createQuestion('Some description', ['one', 'two', 'three'], 'one');

        // Assert
        $this->assertInstanceOf(ChoiceQuestion::class, $question);
    }

    /**
     * @return void
     */
    public function testCreatesQuestionWhenHaveNoChoices(): void
    {
        // Arrange
        $questionFactory = new StringQuestionFactory();

        // Act
        $question = $questionFactory->createQuestion('Some description', [], 'one');

        // Assert
        $this->assertSame(Question::class, get_class($question));
        $this->assertIsCallable($question->getNormalizer());
    }

    /**
     * @return void
     */
    public function testHasValidatorWhenDefaultValueNotSet(): void
    {
        // Arrange
        $questionFactory = new StringQuestionFactory();

        // Act
        $question = $questionFactory->createQuestion('Some description', ['one', 'two', 'three']);

        // Assert
        $this->assertIsCallable($question->getValidator());
    }

    /**
     * @return void
     */
    public function testHasTypeGeneric(): void
    {
        // Act
        $type = StringQuestionFactory::getType();

        // Assert
        $this->assertSame($type, ValueTypeEnum::TYPE_STRING);
    }
}
