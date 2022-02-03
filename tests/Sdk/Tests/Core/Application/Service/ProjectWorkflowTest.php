<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Infrastructure\Repository\ProjectSettingRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowRepository;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

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
        $workflow = $this->createMockWorkflow();
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
        $settingRepository = $this->createMock(ProjectSettingRepository::class);

        return $settingRepository;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Workflow\Registry
     */
    protected function createWorkflowRegistryMock(): Registry
    {
        $workflowRegistry = $this->createMock(Registry::class);

        return $workflowRegistry;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Workflow\Workflow
     */
    protected function createMockWorkflow(): Workflow
    {
        $workflow = $this->createMock(Workflow::class);

        return $workflow;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected function createWorkflowRepositoryMock(): WorkflowRepositoryInterface
    {
        $workflowRepository = $this->createMock(WorkflowRepository::class);

        return $workflowRepository;
    }
}
