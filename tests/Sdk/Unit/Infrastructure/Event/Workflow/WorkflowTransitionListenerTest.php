<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Event\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowTransitionListener;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use stdClass;
use Symfony\Component\Workflow\Event\TransitionEvent;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Metadata\MetadataStoreInterface;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class WorkflowTransitionListenerTest extends Unit
{
    /**
     * @return void
     */
    public function testTransitionExecutesTask(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn(['task' => 'sdk:test:task']);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $context = new Context();
        $event = new TransitionEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $taskExecutorMock = $this->createMock(TaskExecutor::class);
        $taskExecutorMock->expects($this->once())
            ->method('execute')
            ->with('sdk:test:task', $context)
            ->willReturn($context);

        $eventListener = new WorkflowTransitionListener($taskExecutorMock);

        // Act
        $eventListener->execute($event);
    }

    /**
     * @return void
     */
    public function testTransitionWithoutATaskWillSilently(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn([]);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $context = new Context();
        $event = new TransitionEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $taskExecutorMock = $this->createMock(TaskExecutor::class);
        $taskExecutorMock->expects($this->never())
            ->method('execute');

        $eventListener = new WorkflowTransitionListener($taskExecutorMock);

        // Act
        $eventListener->execute($event);
    }

    /**
     * @return void
     */
    public function testTransitionWithoutContextWillThrowException(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn(['task' => 'sdk:test:task']);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $event = new TransitionEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
        );

        $taskExecutorMock = $this->createMock(TaskExecutor::class);
        $taskExecutorMock->expects($this->never())
            ->method('execute');

        $eventListener = new WorkflowTransitionListener($taskExecutorMock);

        // Act
        $this->expectException(NotEnabledTransitionException::class);
        $this->expectExceptionMessage('is not enabled for workflow');

        // Assert
        $eventListener->execute($event);
    }

    /**
     * @return void
     */
    public function testFailedTaskWillThrowException(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn(['task' => 'sdk:test:task']);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $context = new Context();
        $event = new TransitionEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $taskExecutorMock = $this->createMock(TaskExecutor::class);
        $taskExecutorMock->expects($this->once())
            ->method('execute')
            ->with('sdk:test:task', $context)
            ->willReturnCallback(function (string $task, Context $context) {
                $context->setExitCode(ContextInterface::FAILURE_EXIT_CODE);

                return $context;
            });

        $eventListener = new WorkflowTransitionListener($taskExecutorMock);

        // Act
        $this->expectException(NotEnabledTransitionException::class);
        $this->expectExceptionMessage('is not enabled');

        // Assert
        $eventListener->execute($event);
    }
}
