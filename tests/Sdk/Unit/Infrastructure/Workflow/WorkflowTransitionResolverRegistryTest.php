<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Workflow\WorkflowTransitionResolverRegistry;
use SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Workflow
 * @group WorkflowTransitionResolverRegistryTest
 * Add your own group annotations below this line
 */
class WorkflowTransitionResolverRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testReturnsWorkflowTransitionResolverWhenItSet(): void
    {
        // Arrange
        $transitionResolver = $this->createTransitionResolverMock('some_name');
        $workflowTransitionResolverRegistry = new WorkflowTransitionResolverRegistry([$transitionResolver]);

        // Act
        $existentResolver = $workflowTransitionResolverRegistry->getTransitionResolverByName('some_name');

        // Assert
        $this->assertSame($transitionResolver, $existentResolver);
    }

    /**
     * @return void
     */
    public function testReturnsNullWhenResolverNotSet(): void
    {
        // Arrange
        $workflowTransitionResolverRegistry = new WorkflowTransitionResolverRegistry([]);

        // Act
        $nonExistentResolver = $workflowTransitionResolverRegistry->getTransitionResolverByName('not_set');

        // Assert
        $this->assertNull($nonExistentResolver);
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface
     */
    protected function createTransitionResolverMock(string $name): TransitionResolverInterface
    {
        $transitionResolver = $this->createMock(TransitionResolverInterface::class);
        $transitionResolver->method('getName')->willReturn($name);

        return $transitionResolver;
    }
}
