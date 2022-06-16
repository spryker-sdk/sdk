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
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Workflow;
use SprykerSdk\Sdk\Infrastructure\Repository\ProjectSettingRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowRepository;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\Metadata\InMemoryMetadataStore;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\TransitionBlocker;
use Symfony\Component\Workflow\TransitionBlockerList;
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
    public function testApplyTransactionwithBlokers(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();
        $workflowMock->expects($this->once())
            ->method('apply')
            ->willThrowException(
                new NotEnabledTransitionException((object)'test', 'test', $workflowMock, new TransitionBlockerList([new TransitionBlocker('error', 'code')])),
            );
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowRepositoryMock->expects($this->once())
            ->method('flush');

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();
        $context = new Context();

        // Act
        $result = $projectWorkflow->applyTransition('', $context);

        // Assert
        $this->assertNotEmpty($context->getMessages());
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

        $workflowMock = $this->createWorkflowMock();
        $workflowMock->expects($this->once())
            ->method('apply');
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowRepositoryMock->expects($this->once())
            ->method('flush');

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();
        $context = new Context();

        // Act
        $result = $projectWorkflow->applyTransition('', $context);

        // Assert
        $this->assertSame($result, $context);
    }

    /**
     * @return void
     */
    public function testGetNextEnabledTransactions(): void
    {
        // Arrange
        $transactionMock = $this->createMock(Transition::class);
        $transactionMock->expects($this->once())
            ->method('getName')
            ->willReturn('test');
        $transactions = [$transactionMock];
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();
        $workflowMock->expects($this->once())
            ->method('getEnabledTransitions')
            ->willReturn($transactions);
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();

        // Act
        $result = $projectWorkflow->getNextEnabledTransition();

        // Assert
        $this->assertSame($result, ['test']);
    }

    /**
     * @return void
     */
    public function testGetWorkflowMetadata(): void
    {
        // Arrange
        $metadata = ['test'];
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();
        $workflowMock->expects($this->once())
            ->method('getMetadataStore')
            ->willReturn((new InMemoryMetadataStore($metadata)));
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();

        // Act
        $result = $projectWorkflow->getWorkflowMetadata();

        // Assert
        $this->assertSame($result, $metadata);
    }

    /**
     * @return void
     */
    public function testInitializeWorkflow(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();

        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
        );

        // Act
        $result = $projectWorkflow->initializeWorkflow();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testHasWorkflow(): void
    {
        // Arrange
        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
        ->method('hasWorkflow')
        ->willReturn(true);
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $this->createWorkflowRegistryMock(),
            $workflowRepositoryMock,
        );

        // Act
        $result = $projectWorkflow->hasWorkflow();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testFindInitializeWorkflows(): void
    {
        // Arrange
        $workflowEntityMock = $this->createWorkflowEntityMock();
        $workflowEntityMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn('test');
        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findWorkflows')
            ->willReturn([$workflowEntityMock]);
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $this->createWorkflowRegistryMock(),
            $workflowRepositoryMock,
        );

        // Act
        $result = $projectWorkflow->findInitializedWorkflows();

        // Assert
        $this->assertSame(['test'], $result);
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Domain\Entity\Workflow
     */
    protected function createWorkflowEntityMock(): Workflow
    {
        return $this->createMock(Workflow::class);
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
}
