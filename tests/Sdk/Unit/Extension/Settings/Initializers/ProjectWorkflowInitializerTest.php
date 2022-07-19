<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Settings\Initializers;

use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Extension\Settings\Initializers\ProjectWorkflowInitializer;
use SprykerSdk\Sdk\Infrastructure\Entity\Workflow;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Entity\WorkflowInterface;

class ProjectWorkflowInitializerTest extends Unit
{
    /**
     * @return void
     */
    public function testReturnWhenWorkflowNotFound(): void
    {
        //Arrange
        $setting = $this->createSettingsMock([]);
        $projectWorkflow = $this->createProjectWorkflow();
        $projectSettingRepository = $this->createProjectSettingRepositoryMock();
        $workflowRepository = $this->createWorkflowRepositoryMock([$this->createWorkflowMock('')]);
        $projectWorkflowInitializer = new ProjectWorkflowInitializer($projectSettingRepository, $workflowRepository, $projectWorkflow);

        $this->expectMethodSaveCall($workflowRepository, false);

        //Act
        $projectWorkflowInitializer->initialize($setting);
    }

    /**
     * @return void
     */
    public function testReturnWhenProjectSettingsNotFound(): void
    {
        //Arrange
        $setting = $this->createSettingsMock(['test_workflow']);
        $projectWorkflow = $this->createProjectWorkflow();
        $projectSettingRepository = $this->createProjectSettingRepositoryMock();
        $workflowRepository = $this->createWorkflowRepositoryMock([$this->createWorkflowMock('')]);
        $projectWorkflowInitializer = new ProjectWorkflowInitializer($projectSettingRepository, $workflowRepository, $projectWorkflow);

        $this->expectMethodSaveCall($workflowRepository, false);

        //Act
        $projectWorkflowInitializer->initialize($setting);
    }

    /**
     * @return void
     */
    public function testCreateAndSaveWhenNoExistingWorkflows(): void
    {
        //Arrange
        $setting = $this->createSettingsMock(['test_workflow']);
        $projectWorkflow = $this->createProjectWorkflow();
        $projectSettingRepository = $this->createProjectSettingRepositoryMock($this->createProjectSettingMock('project_key'));
        $workflowRepository = $this->createWorkflowRepositoryMock([]);
        $projectWorkflowInitializer = new ProjectWorkflowInitializer($projectSettingRepository, $workflowRepository, $projectWorkflow);

        $this->expectMethodSaveCall($workflowRepository, true, 'project_key', 'test_workflow');

        //Act
        $projectWorkflowInitializer->initialize($setting);
    }

    /**
     * @return void
     */
    public function testReturnWorkflowWhenExistingWorkflowEqualsToSettingWorkflow(): void
    {
        //Arrange
        $setting = $this->createSettingsMock(['test_workflow']);
        $projectWorkflow = $this->createProjectWorkflow();
        $projectSettingRepository = $this->createProjectSettingRepositoryMock($this->createProjectSettingMock('project_key'));
        $workflowRepository = $this->createWorkflowRepositoryMock([$this->createWorkflowMock('test_workflow')]);
        $projectWorkflowInitializer = new ProjectWorkflowInitializer($projectSettingRepository, $workflowRepository, $projectWorkflow);

        $this->expectMethodSaveCall($workflowRepository, false);

        //Act
        $projectWorkflowInitializer->initialize($setting);
    }

    /**
     * @return void
     */
    public function testCreateAndSaveWorkflowWhenExistingWorkflowDifferFromSettingWorkflow(): void
    {
        //Arrange
        $setting = $this->createSettingsMock(['test_workflow']);
        $projectWorkflow = $this->createProjectWorkflow();
        $projectSettingRepository = $this->createProjectSettingRepositoryMock($this->createProjectSettingMock('project_key'));
        $workflowRepository = $this->createWorkflowRepositoryMock([$this->createWorkflowMock('existing_workflow')]);
        $projectWorkflowInitializer = new ProjectWorkflowInitializer($projectSettingRepository, $workflowRepository, $projectWorkflow);

        $this->expectMethodSaveCall($workflowRepository, true, 'project_key', 'test_workflow');

        //Act
        $projectWorkflowInitializer->initialize($setting);
    }

    /**
     * @param array<string> $workflows
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createSettingsMock(array $workflows): SettingInterface
    {
        $settingsMock = $this->createMock(SettingInterface::class);
        $settingsMock->method('getValues')->willReturn($workflows);

        return $settingsMock;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected function createProjectWorkflow(): ProjectWorkflow
    {
        return $this->createMock(ProjectWorkflow::class);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface|null $setting
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected function createProjectSettingRepositoryMock(?SettingInterface $setting = null): ProjectSettingRepositoryInterface
    {
        $projectSettingRepositoryMock = $this->createMock(ProjectSettingRepositoryInterface::class);
        $projectSettingRepositoryMock->method('findOneByPath')->willReturn($setting);

        return $projectSettingRepositoryMock;
    }

    /**
     * @param string $projectKey
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createProjectSettingMock(string $projectKey): SettingInterface
    {
        $projectSettingRepositoryMock = $this->createMock(SettingInterface::class);
        $projectSettingRepositoryMock->method('getValues')->willReturn($projectKey);

        return $projectSettingRepositoryMock;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\WorkflowInterface> $existingWorkflows
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\WorkflowRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createWorkflowRepositoryMock(array $existingWorkflows): WorkflowRepositoryInterface
    {
        $workflowRepositoryMock = $this->createMock(WorkflowRepositoryInterface::class);
        $workflowRepositoryMock->method('findWorkflows')->willReturn($existingWorkflows);

        return $workflowRepositoryMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $workflowRepositoryMock
     * @param bool $isSaveMethodCallExpected
     * @param string $projectKey
     * @param string $workflow
     *
     * @return void
     */
    protected function expectMethodSaveCall(
        MockObject $workflowRepositoryMock,
        bool $isSaveMethodCallExpected,
        string $projectKey = '',
        string $workflow = ''
    ): void {
        $workflowRepositoryMock
            ->expects($isSaveMethodCallExpected ? $this->once() : $this->never())
            ->method('save')
            ->with($this->callback(static function (Workflow $workflowObject) use ($projectKey, $workflow): bool {
                return $workflowObject->getProject() === $projectKey && $workflowObject->getWorkflow() === $workflow;
            }));
    }

    /**
     * @param string $workflowName
     *
     * @return \SprykerSdk\SdkContracts\Entity\WorkflowInterface
     */
    protected function createWorkflowMock(string $workflowName): WorkflowInterface
    {
        $workflowMock = $this->createMock(WorkflowInterface::class);
        $workflowMock->method('getWorkflow')->willReturn($workflowName);

        return $workflowMock;
    }
}
