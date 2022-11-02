<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service\ValueReceiver\QuestionFactory;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\QuestionFactory\PathQuestionFactory;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;
use Symfony\Component\Console\Question\Question;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group ValueReceiver
 * @group QuestionFactory
 * @group PathQuestionFactoryTest
 * Add your own group annotations below this line
 */
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
        $this->assertSame($type, ValueTypeEnum::TYPE_PATH);
    }
}
