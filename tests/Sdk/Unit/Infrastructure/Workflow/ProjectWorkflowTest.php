<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Workflow;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\WorkflowRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\WorkflowTransitionRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\ProjectWorkflowException;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Workflow;
use SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransitionInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\ProjectSettingRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowRepository;
use SprykerSdk\Sdk\Infrastructure\Repository\WorkflowTransitionRepository;
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
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Workflow
 * @group ProjectWorkflowTest
 * Add your own group annotations below this line
 */
class ProjectWorkflowTest extends Unit
{
    /**
     * @return void
     */
    public function testApplyTransitionWithBlockers(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
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
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowRepositoryMock->expects($this->once())
            ->method('flush');

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
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
    public function testRunningTransitionThrowException(): void
    {
        // Arrange
        $projectWorkflow = new ProjectWorkflow(
            $this->createProjectSettingRepositoryMock(),
            $this->createWorkflowRegistryMock(),
            $this->createWorkflowRepositoryMock(),
            $this->createWorkflowTransitionRepositoryMock(),
        );

        // Assert
        $this->expectException(ProjectWorkflowException::class);

        // Act
        $projectWorkflow->getRunningTransition();
    }

    /**
     * @return void
     */
    public function testRunningTransitionFinished(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowTransitionMock = $this->createWorkflowTransitionMock();
        $workflowTransitionMock->expects($this->once())
            ->method('getState')
            ->willReturn('transition_finished');

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->once())
            ->method('findLast')
            ->willReturn($workflowTransitionMock);

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();

        // Act
        $workflowTransition = $projectWorkflow->getRunningTransition();

        // Assert
        $this->assertNull($workflowTransition);
    }

    /**
     * @return void
     */
    public function testRunningTransition(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowTransitionMock = $this->createWorkflowTransitionMock();
        $workflowTransitionMock->expects($this->once())
            ->method('getState')
            ->willReturn('transition_finished1');

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->once())
            ->method('findLast')
            ->willReturn($workflowTransitionMock);

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();

        // Act
        $workflowTransition = $projectWorkflow->getRunningTransition();

        // Assert
        $this->assertSame($workflowTransitionMock, $workflowTransition);
    }

    /**
     * @return void
     */
    public function testPreviousTransitionThrowException(): void
    {
        // Arrange
        $projectWorkflow = new ProjectWorkflow(
            $this->createProjectSettingRepositoryMock(),
            $this->createWorkflowRegistryMock(),
            $this->createWorkflowRepositoryMock(),
            $this->createWorkflowTransitionRepositoryMock(),
        );

        // Assert
        $this->expectException(ProjectWorkflowException::class);

        // Act
        $projectWorkflow->findPreviousTransition();
    }

    /**
     * @return void
     */
    public function testPreviousTransitionFinished(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowTransitionMock = $this->createWorkflowTransitionMock();
        $workflowTransitionMock->expects($this->once())
            ->method('getState')
            ->willReturn('transition');

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->once())
            ->method('findLast')
            ->willReturn($workflowTransitionMock);

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();

        // Act
        $workflowTransition = $projectWorkflow->findPreviousTransition();

        // Assert
        $this->assertNull($workflowTransition);
    }

    /**
     * @return void
     */
    public function testPreviousTransition(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();
        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowTransitionMock = $this->createWorkflowTransitionMock();
        $workflowTransitionMock->expects($this->once())
            ->method('getState')
            ->willReturn('transition_finished');

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();
        $workflowTransitionRepositoryMock->expects($this->once())
            ->method('findLast')
            ->willReturn($workflowTransitionMock);

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();

        // Act
        $workflowTransition = $projectWorkflow->findPreviousTransition();

        // Assert
        $this->assertSame($workflowTransitionMock, $workflowTransition);
    }

    /**
     * @return void
     */
    public function testApplyTransition(): void
    {
        // Arrange
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
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
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowRepositoryMock->expects($this->once())
            ->method('flush');

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
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
    public function testGetNextEnabledTransitions(): void
    {
        // Arrange
        $transactionMock = $this->createMock(Transition::class);
        $transactionMock->expects($this->once())
            ->method('getName')
            ->willReturn('test');
        $transactions = [$transactionMock];
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
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
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );
        $projectWorkflow->initializeWorkflow();

        // Act
        $result = $projectWorkflow->getNextEnabledTransitions();

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
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
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
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
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
        $projectSettingRepositoryMock->expects($this->atLeastOnce())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowMock = $this->createWorkflowMock();

        $workflowRegistry = $this->createWorkflowRegistryMock();
        $workflowRegistry->expects($this->once())
            ->method('get')
            ->willReturn($workflowMock);

        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('findWorkflow')
            ->willReturn(new Workflow('', [], 'default'));

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $workflowRegistry,
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
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

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $this->createWorkflowRegistryMock(),
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
        );

        // Act
        $result = $projectWorkflow->hasWorkflow();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testFindInitializedWorkflows(): void
    {
        // Arrange
        $workflowEntityMock = $this->createWorkflowEntityMock();
        $workflowEntityMock->expects($this->once())
            ->method('getWorkflow')
            ->willReturn('test');
        $workflowRepositoryMock = $this->createWorkflowRepositoryMock();
        $workflowRepositoryMock->expects($this->once())
            ->method('getWorkflows')
            ->willReturn([$workflowEntityMock]);
        $projectSettingRepositoryMock = $this->createProjectSettingRepositoryMock();
        $projectSettingRepositoryMock->expects($this->once())
            ->method('getOneByPath')
            ->willReturn($this->createSettingMock());

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $projectSettingRepositoryMock,
            $this->createWorkflowRegistryMock(),
            $workflowRepositoryMock,
            $workflowTransitionRepositoryMock,
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

        $workflowTransitionRepositoryMock = $this->createWorkflowTransitionRepositoryMock();

        $projectWorkflow = new ProjectWorkflow(
            $this->createProjectSettingRepositoryMock(),
            $workflowRegistry,
            $this->createWorkflowRepositoryMock(),
            $workflowTransitionRepositoryMock,
        );

        // Act
        $result = $projectWorkflow->getAll();

        // Assert
        $this->assertNotEmpty($result);
        $this->assertEquals(['default'], $result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Application\Dependency\Repository\WorkflowRepositoryInterface
     */
    protected function createWorkflowRepositoryMock(): WorkflowRepositoryInterface
    {
        return $this->createMock(WorkflowRepository::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Application\Dependency\Repository\WorkflowTransitionRepositoryInterface
     */
    protected function createWorkflowTransitionRepositoryMock(): WorkflowTransitionRepositoryInterface
    {
        return $this->createMock(WorkflowTransitionRepository::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function createContextMock(): ContextInterface
    {
        return $this->createMock(ContextInterface::class);
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\WorkflowTransitionInterface
     */
    protected function createWorkflowTransitionMock(): WorkflowTransitionInterface
    {
        return $this->createMock(WorkflowTransitionInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createSettingMock(): SettingInterface
    {
        $settingMock = $this->createMock(SettingInterface::class);
        $settingMock->expects($this->atLeastOnce())
            ->method('getValues')
            ->willReturn('projectKey');

        return $settingMock;
    }
}
