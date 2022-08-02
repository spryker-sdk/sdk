<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Extension\Workflow\TransitionBooleanResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class TransitionBooleanResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testResolveTransitionFailed(): void
    {
        // Arrange
        $resolveTransition = new TransitionBooleanResolver();
        $settings = [
            TransitionBooleanResolver::FAILED => 'transition1',
            TransitionBooleanResolver::SUCCESSFUL => 'transition2',
        ];
        $context = $this->createMock(ContextInterface::class);
        $context->expects($this->once())
            ->method('getExitCode')
            ->willReturn(ContextInterface::FAILURE_EXIT_CODE);
        // Act
        $result = $resolveTransition->resolveTransition($context, $settings);

        // Assert
        $this->assertSame('transition1', $result);
    }

    /**
     * @return void
     */
    public function testResolveTransitionSuccessful(): void
    {
        // Arrange
        $resolveTransition = new TransitionBooleanResolver();
        $settings = [
            TransitionBooleanResolver::FAILED => 'transition1',
            TransitionBooleanResolver::SUCCESSFUL => 'transition2',
        ];
        $context = $this->createMock(ContextInterface::class);
        $context->expects($this->once())
            ->method('getExitCode')
            ->willReturn(ContextInterface::SUCCESS_EXIT_CODE);
        // Act
        $result = $resolveTransition->resolveTransition($context, $settings);

        // Assert
        $this->assertSame('transition2', $result);
    }
}
