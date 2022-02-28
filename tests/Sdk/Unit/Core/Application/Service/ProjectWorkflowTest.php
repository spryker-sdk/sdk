<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Domain\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Repository\ProjectSettingRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowRepository;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Component\Workflow\Metadata\MetadataStoreInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow as SymfonyWorkflow;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ProjectWorkflowTest
 */
class ProjectWorkflowTest extends Unit
{
    /**
     * @return void
     */
    public function testInitWorkflowSuccessfulInitWithEmptyProjectKey(): void
    {
        // Arrange
        $settingMock = $this->createSettingMock();
        $settingMock->expects($this->once())
            ->method('getValues')
            ->willReturn('test');
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($settingMock);
        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findOne')
            ->willReturn(null);

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $this->createWorkflowRegistryMock(),
            $workflowRepositoryMock,
        );
        $context = $this->createContextMock();

        //Act
        $result = $projectWorkflow->initWorkflow($context);

        // Assert
        $this->asserttrue($result);
    }

    /**
     * @return void
     */
    public function testInitWorkflowSuccessfulInitWithAbsentTask(): void
    {
        // Arrange
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->expects($this->once())
            ->method('getId')
            ->willReturn('test');
        $context = $this->createContextMock();
        $context->expects($this->once())
            ->method('setExitCode');
        $context->expects($this->once())
            ->method('addMessage');
        $context->expects($this->once())
            ->method('getTask')
            ->willReturn($taskMock);

        //Act
        $result = $this->createProjectWorkflow()->initWorkflow($context);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testInitWorkflowSuccessfulInit(): void
    {
        // Arrange
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->expects($this->once())
            ->method('getId')
            ->willReturn('testTask');
        $context = $this->createContextMock();
        $context->expects($this->once())
            ->method('getTask')
            ->willReturn($taskMock);

        //Act
        $result = $this->createProjectWorkflow()->initWorkflow($context);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testApplyTransactionWithError(): void
    {
        // Arrange
        $projectWorkflow = $this->createProjectWorkflow();
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn('testTask');
        $context = $this->createContextMock();
        $context->expects($this->exactly(2))
            ->method('getTask')
            ->willReturn($taskMock);
        $context->expects($this->once())
            ->method('addMessage');
        $context->expects($this->once())
            ->method('getExitCode')
            ->willReturn(1);

        $projectWorkflow->initWorkflow($context);

        //Act
        $projectWorkflow->applyTransaction($context);
    }

    /**
     * @return void
     */
    public function testApplyTransaction(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $workflowMock = $this->createWorkflowMock();
        $workflowMock->expects($this->once())
            ->method('apply');
        $transactionMock = $this->createMock(Transition::class);
        $transactionMock->expects($this->once())
            ->method('getName')
            ->willReturn('test');
        $metadataStoreMock->expects($this->once())
            ->method('getTransitionMetadata')
            ->willReturn(['task' => 'testTask']);
        $workflowMock->expects($this->once())
            ->method('getEnabledTransitions')
            ->willReturn([$transactionMock]);
        $workflowMock->expects($this->once())
            ->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findOne')
            ->willReturn(new Workflow('', [], 'default'));

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->expects($this->once())
            ->method('getId')
            ->willReturn('testTask');
        $context = $this->createContextMock();
        $context->expects($this->once())
            ->method('getTask')
            ->willReturn($taskMock);

        $projectWorkflow->initWorkflow($context);

        //Act
        $projectWorkflow->applyTransaction($context);
    }

    /**
     * @return void
     */
    public function testGetAllWorkflows(): void
    {
        // Arrange
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflow = $this->createWorkflowMock();
        $workflow->expects($this->once())
            ->method('getName')
            ->willReturn('default');
        $workflowRegistry->expects($this->once())
            ->method('all')
            ->willReturn([
                $workflow,
            ]);

        $projectWorkflow = new ProjectWorkflow(
            $this->createProjectSettingRepositoryMock(),
            $workflowRegistry,
            $this->createWorkflowRepositoryMock(),
        );

        // Act
        $result = $projectWorkflow->getAll();

        // Assert
        $this->assertNotEmpty($result);
        $this->assertEquals(['default'], $result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected function createProjectSettingRepositoryMock(): ProjectSettingRepositoryInterface
    {
        return $this->createMock(ProjectSettingRepository::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Workflow\Registry
     */
    protected function createWorkflowRegistryMock(): Registry
    {
        return $this->createMock(Registry::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Workflow\Workflow
     */
    protected function createWorkflowMock(): SymfonyWorkflow
    {
        return $this->createMock(SymfonyWorkflow::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected function createWorkflowRepositoryMock(): WorkflowRepositoryInterface
    {
        return $this->createMock(WorkflowRepository::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function createContextMock(): ContextInterface
    {
        return $this->createMock(ContextInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createSettingMock(): SettingInterface
    {
        $settingMock = $this->createMock(SettingInterface::class);
        $settingMock->expects($this->once())
            ->method('getValues')
            ->willReturn('projectKey');

        return $settingMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected function createProjectWorkflow(): ProjectWorkflow
    {
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $metadataStoreMock = $this->createMock(MetadataStoreInterface::class);
        $workflowMock = $this->createWorkflowMock();
        $metadataStoreMock->expects($this->once())
            ->method('getTransitionMetadata')
            ->willReturn(['task' => 'testTask']);
        $workflowMock->expects($this->once())
            ->method('getEnabledTransitions')
            ->willReturn([$this->createMock(Transition::class)]);
        $workflowMock->expects($this->once())
            ->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findOne')
            ->willReturn(new Workflow('', [], 'default'));

        return new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );
    }
}
