<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowTransitionResolverRegistry;
use SprykerSdk\SdkContracts\Workflow\TransitionResolverInterface;

class WorkflowTransitionResolverRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testReturnWorkflowTransitionResolverWhenItSet(): void
    {
        // Arrange
        $transitionResolver = $this->createTransitionResolverMock('some_name');
        $workflowTransitionResolverRegistry = new WorkflowTransitionResolverRegistry([$transitionResolver]);

        // Act
        $existentResolver = $workflowTransitionResolverRegistry->getTransitionResolverByName('some_name');
        $nonExistentResolver = $workflowTransitionResolverRegistry->getTransitionResolverByName('not_set');

        // Assert
        $this->assertSame($transitionResolver, $existentResolver);
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
