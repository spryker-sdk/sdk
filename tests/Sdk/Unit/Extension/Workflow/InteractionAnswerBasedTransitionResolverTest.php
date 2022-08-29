<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\Workflow\InteractionAnswerBasedTransitionResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueReceiver\ValueReceiverInterface;

class InteractionAnswerBasedTransitionResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testResolveTransitionFailed(): void
    {
        // Arrange
        $valueReceiver = $this->createMock(ValueReceiverInterface::class);
        $valueReceiver->expects($this->once())
            ->method('receiveValue')
            ->willReturn('Choice two description.');
        $context = $this->createMock(ContextInterface::class);

        $resolveTransition = new InteractionAnswerBasedTransitionResolver($valueReceiver);

        $settings = [
            InteractionAnswerBasedTransitionResolver::QUESTION => 'Interactive question',
            InteractionAnswerBasedTransitionResolver::CHOICES => [
                'choice one' => [
                    'description' => 'Choice one description.',
                ],
                'choice two' => [
                    'description' => 'Choice two description.',
                ],
            ],
        ];

        // Act
        $transition = $resolveTransition->resolveTransition($context, $settings);

        // Assert
        $this->assertSame('choice two', $transition);
    }
}
