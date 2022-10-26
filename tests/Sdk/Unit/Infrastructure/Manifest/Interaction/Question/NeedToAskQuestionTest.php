<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Manifest\Interaction\Question;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Question\NeedToAskQuestion;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Manifest
 * @group Interaction
 * @group Question
 * @group NeedToAskQuestionTest
 * Add your own group annotations below this line
 */
class NeedToAskQuestionTest extends Unit
{
    /**
     * @return void
     */
    public function testAskShouldReturnUserValue(): void
    {
        // Arrange
        $interactionProcessor = $this->createInteractionProcessorMock();
        $question = new NeedToAskQuestion($interactionProcessor);

        // Act
        $question->ask('test id');
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected function createInteractionProcessorMock(): InteractionProcessorInterface
    {
        $interactionProcessorMock = $this->createMock(InteractionProcessorInterface::class);
        $interactionProcessorMock
            ->expects($this->once())
            ->method('receiveValue')
            ->with($this->isInstanceOf(ReceiverValue::class))
            ->willReturn(true);

        return $interactionProcessorMock;
    }
}
