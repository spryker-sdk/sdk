<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Event\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransition;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowTransitionListener;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowTransitionRepository;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowTransitionInterface;
use Symfony\Component\Workflow\Event\TransitionEvent;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Metadata\MetadataStoreInterface;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow as SymfonyWorkflow;

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

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowEntity = new Workflow('project', [], 'workflow');

        $context = new Context();
        $event = new TransitionEvent(
            $workflowEntity,
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $taskExecutorMock = $this->createTaskExecutorMock();
        $taskExecutorMock->expects($this->once())
            ->method('execute')
            ->with('sdk:test:task', $context)
            ->willReturn($context);

        $projectWorkflowMock = $this->createProjectWorkflowMock();
        $projectWorkflowMock->expects($this->once())
            ->method('getRunningTransition')
            ->with($workflowEntity)
            ->willReturn(null);

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->atLeastOnce())
            ->method('save')
            ->willReturnCallback(fn (WorkflowTransition $transition): WorkflowTransition => $transition);

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $projectWorkflowMock,
            $this->createWorkflowRepositoryMock(),
            $workflowTransitionRepositoryMock,
        );

        // Act
        $eventListener->execute($event);
    }

    /**
     * @return void
     */
    public function testTransitionWithoutATaskWillBeSilentlyApplied(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn([]);

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowEntity = new Workflow('project', [], 'workflow');

        $context = new Context();
        $event = new TransitionEvent(
            $workflowEntity,
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $taskExecutorMock = $this->createTaskExecutorMock();
        $taskExecutorMock->expects($this->never())
            ->method('execute');

        $projectWorkflowMock = $this->createProjectWorkflowMock();
        $projectWorkflowMock->expects($this->once())
            ->method('getRunningTransition')
            ->with($workflowEntity)
            ->willReturn(null);

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->atLeastOnce())
            ->method('save')
            ->willReturnCallback(fn (WorkflowTransition $transition): WorkflowTransition => $transition);

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $projectWorkflowMock,
            $this->createWorkflowRepositoryMock(),
            $workflowTransitionRepositoryMock,
        );

        // Act
        $eventListener->execute($event);
    }

    /**
     * @return void
     */
    public function testTransitionWillThrowExceptionWhenAnotherOneIsRunning(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn(['task' => 'sdk:test:task']);

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowEntity = new Workflow('project', [], 'workflow');

        $event = new TransitionEvent(
            $workflowEntity,
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
        );

        $taskExecutorMock = $this->createTaskExecutorMock();
        $taskExecutorMock->expects($this->never())
            ->method('execute');

        $projectWorkflowMock = $this->createProjectWorkflowMock();
        $projectWorkflowMock->expects($this->once())
            ->method('getRunningTransition')
            ->with($workflowEntity)
            ->willReturn(new WorkflowTransition([], 'not_test', WorkflowTransitionInterface::WORKFLOW_TRANSITION_STARTED));

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $projectWorkflowMock,
            $this->createWorkflowRepositoryMock(),
            $this->createWorkflowTransitionRepositoryMock(),
        );

        // Act
        $this->expectException(NotEnabledTransitionException::class);
        $this->expectExceptionMessage('is not enabled for workflow');

        // Assert
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

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowEntity = new Workflow('project', [], 'workflow');

        $event = new TransitionEvent(
            $workflowEntity,
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
        );

        $taskExecutorMock = $this->createTaskExecutorMock();
        $taskExecutorMock->expects($this->never())
            ->method('execute');

        $projectWorkflowMock = $this->createProjectWorkflowMock();
        $projectWorkflowMock->expects($this->once())
            ->method('getRunningTransition')
            ->with($workflowEntity)
            ->willReturn(null);

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->atLeastOnce())
            ->method('save')
            ->willReturnCallback(fn (WorkflowTransition $transition): WorkflowTransition => $transition);

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $projectWorkflowMock,
            $this->createWorkflowRepositoryMock(),
            $workflowTransitionRepositoryMock,
        );

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

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowEntity = new Workflow('project', [], 'workflow');

        $context = new Context();
        $event = new TransitionEvent(
            $workflowEntity,
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $taskExecutorMock = $this->createTaskExecutorMock();
        $taskExecutorMock->expects($this->once())
            ->method('execute')
            ->with('sdk:test:task', $context)
            ->willReturnCallback(function (string $task, Context $context) {
                $context->setExitCode(ContextInterface::FAILURE_EXIT_CODE);

                return $context;
            });

        $projectWorkflowMock = $this->createProjectWorkflowMock();
        $projectWorkflowMock->expects($this->once())
            ->method('getRunningTransition')
            ->with($workflowEntity)
            ->willReturn(null);

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->atLeastOnce())
            ->method('save')
            ->willReturnCallback(fn (WorkflowTransition $transition): WorkflowTransition => $transition);

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $projectWorkflowMock,
            $this->createWorkflowRepositoryMock(),
            $workflowTransitionRepositoryMock,
        );

        // Act
        $this->expectException(NotEnabledTransitionException::class);
        $this->expectExceptionMessage('is not enabled');

        // Assert
        $eventListener->execute($event);
    }

    /**
     * @return void
     */
    public function testNestedWorkflowExecuting(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn([WorkflowTransitionListener::META_KEY_WORKFLOW_BEFORE => 'nested_workflow']);

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowEntity = new Workflow('project', [], 'workflow');
        $nestedWorkflowEntity = new Workflow('project', [], 'nested_workflow', 'test.workflowBefore.nested_workflow');

        $context = new Context();
        $event = new TransitionEvent(
            $workflowEntity,
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $workflowRunnerMock = $this->createWorkflowRunnerMock();
        $workflowRunnerMock->expects($this->once())
            ->method('execute')
            ->with('test.workflowBefore.nested_workflow', $context);

        $projectWorkflowMock = $this->createProjectWorkflowMock();
        $projectWorkflowMock->expects($this->once())
            ->method('getRunningTransition')
            ->with($workflowEntity)
            ->willReturn(null);
        $projectWorkflowMock->expects($this->exactly(2))
            ->method('isWorkflowFinished')
            ->willReturnOnConsecutiveCalls(false, true);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->exactly(2))
            ->method('getWorkflow')
            ->with('project', 'test.workflowBefore.nested_workflow')
            ->willReturn($nestedWorkflowEntity);

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->atLeastOnce())
            ->method('save')
            ->willReturnCallback(fn (WorkflowTransition $transition): WorkflowTransition => $transition);

        $eventListener = new WorkflowTransitionListener(
            $this->createTaskExecutorMock(),
            $workflowRunnerMock,
            $projectWorkflowMock,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );

        // Act
        $eventListener->execute($event);
    }

    /**
     * @return void
     */
    public function testExceptionIsThrownIfNestedWorkflowIsNotFinished(): void
    {
        // Arrange
        $transition = new Transition('test', [], []);
        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $metadataStoreMock->method('getTransitionMetadata')
            ->with($transition)
            ->willReturn([WorkflowTransitionListener::META_KEY_WORKFLOW_BEFORE => 'nested_workflow']);

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
        $workflowMock->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowEntity = new Workflow('project', [], 'workflow');
        $nestedWorkflowEntity = new Workflow('project', [], 'nested_workflow', 'test.workflowBefore.nested_workflow');

        $context = new Context();
        $event = new TransitionEvent(
            $workflowEntity,
            new Marking(),
            new Transition('test', [], []),
            $workflowMock,
            ['context' => $context],
        );

        $workflowRunnerMock = $this->createWorkflowRunnerMock();
        $workflowRunnerMock->expects($this->once())
            ->method('execute')
            ->with('test.workflowBefore.nested_workflow', $context);

        $projectWorkflowMock = $this->createProjectWorkflowMock();
        $projectWorkflowMock->expects($this->once())
            ->method('getRunningTransition')
            ->with($workflowEntity)
            ->willReturn(null);
        $projectWorkflowMock->expects($this->exactly(2))
            ->method('isWorkflowFinished')
            ->willReturnOnConsecutiveCalls(false, false);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->exactly(2))
            ->method('getWorkflow')
            ->with('project', 'test.workflowBefore.nested_workflow')
            ->willReturn($nestedWorkflowEntity);

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->atLeastOnce())
            ->method('save')
            ->willReturnCallback(fn (WorkflowTransition $transition): WorkflowTransition => $transition);

        $eventListener = new WorkflowTransitionListener(
            $this->createTaskExecutorMock(),
            $workflowRunnerMock,
            $projectWorkflowMock,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );

        // Act
        $this->expectException(NotEnabledTransitionException::class);
        $this->expectExceptionMessage('is not enabled');

        // Assert
        $eventListener->execute($event);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor
     */
    protected function createTaskExecutorMock(): TaskExecutor
    {
        return $this->createMock(TaskExecutor::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner
     */
    protected function createWorkflowRunnerMock(): WorkflowRunner
    {
        return $this->createMock(WorkflowRunner::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected function createProjectWorkflowMock(): ProjectWorkflow
    {
        return $this->createMock(ProjectWorkflow::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected function createWorkflowRepositoryMock(): WorkflowRepositoryInterface
    {
        return $this->createMock(WorkflowRepository::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowTransitionRepositoryInterface
     */
    protected function createWorkflowTransitionRepositoryMock(): WorkflowTransitionRepositoryInterface
    {
        return $this->createMock(WorkflowTransitionRepository::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Workflow\Workflow
     */
    protected function createSymfonyWorkflowMock(): SymfonyWorkflow
    {
        return $this->createMock(SymfonyWorkflow::class);
    }
}
