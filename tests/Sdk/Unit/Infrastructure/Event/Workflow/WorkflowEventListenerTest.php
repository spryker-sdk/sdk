<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Event\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Extension\Dependency\Event\WorkflowEventHandlerInterface;
use SprykerSdk\Sdk\Extension\Dependency\Event\WorkflowGuardEventHandlerInterface;
use SprykerSdk\Sdk\Extension\Exception\InvalidServiceException;
use SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowEvent;
use SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowEventListener;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\InteractionProcessor;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Event\LeaveEvent;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Metadata\MetadataStoreInterface;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class WorkflowEventListenerTest extends Unit
{
    /**
     * @return void
     */
    public function testGuardIsAppliedOnWorkflow(): void
    {
        // Arrange
        $cliInteractionProcessorMock = $this->createMock(InteractionProcessor::class);
        $guardMock = $this->createMock(WorkflowGuardEventHandlerInterface::class);
        $guardMock->method('check')
            ->willReturnCallback(function (GuardEvent $e) {
                $e->setBlocked('Blocked by test');
            });

        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getWorkflowMetadata')
            ->willReturn(['guard' => 'guard_service']);
        $metadataStoreMock->method('getTransitionMetadata')
            ->willReturn([]);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $event = new GuardEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
        );

        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->method('get')
            ->with('guard_service')
            ->willReturn($guardMock);

        $eventListener = new WorkflowEventListener($containerMock, $cliInteractionProcessorMock);

        // Act
        $eventListener->guard($event);

        // Assert
        $this->assertTrue($event->isBlocked());
    }

    /**
     * @return void
     */
    public function testGuardIsAppliedOnTransition(): void
    {
        // Arrange
        $cliInteractionProcessorMock = $this->createMock(InteractionProcessor::class);
        $guardMock = $this->createMock(WorkflowGuardEventHandlerInterface::class);
        $guardMock->method('check')
            ->willReturnCallback(function (GuardEvent $e) {
                $e->setBlocked('Blocked by test');
            });

        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getWorkflowMetadata')
            ->willReturn([]);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn(['guard' => 'guard_service']);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $event = new GuardEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
        );

        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->method('get')
            ->with('guard_service')
            ->willReturn($guardMock);

        $eventListener = new WorkflowEventListener($containerMock, $cliInteractionProcessorMock);

        // Act
        $eventListener->guard($event);

        // Assert
        $this->assertTrue($event->isBlocked());
    }

    /**
     * @return void
     */
    public function testHandlerIsCalledOnTransition(): void
    {
        // Arrange
        $cliInteractionProcessorMock = $this->createMock(InteractionProcessor::class);
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getWorkflowMetadata')
            ->willReturn([]);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn(['before' => 'before_handler']);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $event = new LeaveEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => new Context()],
        );

        $handlerMock = $this->createMock(WorkflowEventHandlerInterface::class);
        $handlerMock->expects($this->once())
            ->method('handle')
            ->willReturnCallback(function (WorkflowEvent $e) use ($event) {
                $this->assertInstanceOf(Context::class, $e->getContext());
                $this->assertSame($event, $e->getEvent());
            });

        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->method('get')
            ->with('before_handler')
            ->willReturn($handlerMock);

        $eventListener = new WorkflowEventListener($containerMock, $cliInteractionProcessorMock);

        // Act
        $eventListener->handle($event);
    }

    /**
     * @return void
     */
    public function testInvalidHandlerThrowsMeaningfulException(): void
    {
        // Arrange
        $cliInteractionProcessorMock = $this->createMock(InteractionProcessor::class);
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getWorkflowMetadata')
            ->willReturn([]);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn(['before' => 'before_handler']);

        $workflowMock = $this->createMock(Workflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $event = new LeaveEvent(
            new stdClass(),
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => new Context()],
        );

        $handlerMock = $this->createMock(WorkflowGuardEventHandlerInterface::class);

        $containerMock = $this->createMock(ContainerInterface::class);
        $containerMock->method('get')
            ->with('before_handler')
            ->willReturn($handlerMock);

        $eventListener = new WorkflowEventListener($containerMock, $cliInteractionProcessorMock);

        // Act
        $this->expectException(InvalidServiceException::class);

        // Assert
        $eventListener->handle($event);
    }
}
