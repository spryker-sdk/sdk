<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\ValueReceiver\QuestionFactory;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\ArrayQuestionFactory;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group ValueReceiver
 * @group QuestionFactory
 * @group ArrayQuestionFactoryTest
 */
class ArrayQuestionFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreatesChoiceQuestionWithDescriptionAndMultiSelectWhenHaveChoices(): void
    {
        // Arrange
        $questionFactory = new ArrayQuestionFactory();

        // Act
        /** @var \Symfony\Component\Console\Question\ChoiceQuestion $question */
        $question = $questionFactory->createQuestion('Some description', ['one', 'two', 'three'], 'one');

        // Assert
        $this->assertInstanceOf(ChoiceQuestion::class, $question);
        $this->assertStringContainsString(
            \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\ArrayQuestionFactory::CHOICE_DESCRIPTION_SUFFIX, $question->getQuestion());
        $this->assertTrue($question->isMultiSelect());
    }

    /**
     * @return void
     */
    public function testCreatesQuestionWithMultiLineWhenHaveNoChoices(): void
    {
        // Arrange
        $questionFactory = new ArrayQuestionFactory();

        // Act
        $question = $questionFactory->createQuestion('Some description', [], 'one');

        // Assert
        $this->assertSame(Question::class, get_class($question));
        $this->assertTrue($question->isMultiline());
    }

    /**
     * @return void
     */
    public function testHasTypeArray(): void
    {
        // Act
        $type = ArrayQuestionFactory::getType();

        // Assert
        $this->assertSame($type, ValueTypeEnum::TYPE_ARRAY);
    }
}
