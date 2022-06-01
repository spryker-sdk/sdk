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
        $metadataStoreMock
            ->method('getTransitionMetadata')
            ->willReturn(['task' => 'testTask']);
        $workflowMock->expects($this->once())
            ->method('getEnabledTransitions')
            ->willReturn([$this->createMock(Transition::class)]);
        $workflowMock
            ->method('getMetadataStore')
            ->willReturn($metadataStoreMock);

        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        return new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );
    }
}
