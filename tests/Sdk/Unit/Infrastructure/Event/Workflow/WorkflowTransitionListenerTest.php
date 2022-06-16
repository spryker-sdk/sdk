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
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Event\Workflow\WorkflowTransitionListener;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowTransitionRepository;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use stdClass;
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

        $taskExecutorMock = $this->createMock(TaskExecutor::class);
        $taskExecutorMock->expects($this->once())
            ->method('execute')
            ->with('sdk:test:task', $context)
            ->willReturn($context);

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $this->createProjectWorkflowMock(),
            $this->createWorkflowRepositoryMock(),
            $this->createWorkflowTransitionRepositoryMock(),
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

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $this->createProjectWorkflowMock(),
            $this->createWorkflowRepositoryMock(),
            $this->createWorkflowTransitionRepositoryMock(),
        );

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

        $workflowMock = $this->createMock(SymfonyWorkflow::class);
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

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $this->createProjectWorkflowMock(),
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

        $eventListener = new WorkflowTransitionListener(
            $taskExecutorMock,
            $this->createWorkflowRunnerMock(),
            $this->createProjectWorkflowMock(),
            $this->createWorkflowRepositoryMock(),
            $this->createWorkflowTransitionRepositoryMock(),
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
