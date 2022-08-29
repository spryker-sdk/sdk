<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\CliValueReceiver\QuestionFactory;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionFactory\PathQuestionFactory;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver\QuestionTypeEnum;
use Symfony\Component\Console\Question\Question;

class PathQuestionFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreatesBooleanQuestion(): void
    {
        // Arrange
        $questionFactory = new PathQuestionFactory();

        // Act
        $question = $questionFactory->createQuestion('Some description', ['one', 'two', 'three'], 'one');

        // Assert
        $this->assertInstanceOf(Question::class, $question);
        $this->assertIsCallable($question->getAutocompleterCallback());
    }

    /**
     * @return void
     */
    public function testHasTypeBoolean(): void
    {
        // Act
        $type = PathQuestionFactory::getType();

        // Assert
        $this->assertSame($type, QuestionTypeEnum::TYPE_PATH);
    }
}
