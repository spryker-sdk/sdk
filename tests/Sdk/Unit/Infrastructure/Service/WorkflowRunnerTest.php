<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @group tretret
 */
class WorkflowRunnerTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\CliInteractionProcessor
     */
    protected CliInteractionProcessor $cliValueReceiver;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected OutputInterface $output;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @var \SprykerSdk\Sdk\Core\Domain\Entity\ContextInterface
     */
    protected ContextInterface $context;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->cliValueReceiver = $this->createMock(CliInteractionProcessor::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->projectWorkflow = $this->createMock(ProjectWorkflow::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->contextFactory = $this->createMock(ContextFactoryInterface::class);
        $this->context = $this->createMock(ContextInterface::class);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testExecuteWithNotInitializeWorkflow(): void
    {
        // Arrange
        $this->projectWorkflow
            ->expects($this->once())
            ->method('initializeWorkflow')
            ->willReturn(false);
        $this->container
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->projectWorkflow);
        $this->context
            ->expects($this->once())
            ->method('addMessage')
            ->with(
                'workflowName',
                new Message('Workflow `workflowName` can not be initialized.', Message::ERROR),
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);

        // Act
        $workflowRunner->execute('workflowName', $this->context);
    }

    /**
     * @return void
     */
    public function testExecuteWithMetadataAndFinished(): void
    {
        // Arrange
        $this->projectWorkflow
            ->expects($this->once())
            ->method('initializeWorkflow')
            ->willReturn(true);
        $this->projectWorkflow
            ->expects($this->once())
            ->method('getWorkflowMetadata')
            ->willReturn(['run' => 'single', 're-run' => true]);
        $this->projectWorkflow
            ->expects($this->once())
            ->method('isWorkflowFinished')
            ->willReturn(true);
        $this->projectWorkflow
            ->expects($this->once())
            ->method('restartWorkflow');
        $this->container
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->projectWorkflow);
        $this->context
            ->expects($this->once())
            ->method('addMessage')
            ->with(
                'workflowName',
                new Message('The workflow `workflowName` has been finished.', Message::ERROR),
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);

        // Act
        $workflowRunner->execute('workflowName', $this->context);
    }

    /**
     * @return void
     */
    public function testExecuteWithMetadata(): void
    {
        // Arrange
        $this->projectWorkflow
            ->expects($this->once())
            ->method('initializeWorkflow')
            ->willReturn(true);
        $this->projectWorkflow
            ->expects($this->once())
            ->method('getNextEnabledTransitions')
            ->willReturn(['test'], ['test']);
        $this->projectWorkflow
            ->expects($this->once())
            ->method('applyTransition');
        $this->projectWorkflow
            ->expects($this->once())
            ->method('getWorkflowMetadata')
            ->willReturn(['run' => 'single']);
        $this->container
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->projectWorkflow);
        $this->context
            ->method('addMessage')
            ->withConsecutive([
                'workflowName', new Message('Applying transition `workflowName:test`.', Message::INFO),
                'workflowName:test', new Message('The `workflowName:test` transition finished successfully.', Message::INFO),
            ]);

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);

        // Act
        $workflowRunner->execute('workflowName', $this->context);
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        // Arrange
        $this->projectWorkflow
            ->expects($this->once())
            ->method('initializeWorkflow')
            ->willReturn(true);
        $this->projectWorkflow
            ->expects($this->exactly(3))
            ->method('getNextEnabledTransitions')
            ->willReturn(['test'], ['test'], []);
        $this->projectWorkflow
            ->expects($this->exactly(2))
            ->method('applyTransition');
        $this->projectWorkflow
            ->expects($this->once())
            ->method('getWorkflowMetadata')
            ->willReturn([]);
        $this->container
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->projectWorkflow);
        $this->context
            ->method('addMessage')
            ->with(
                'workflowName',
                new Message('The workflow `workflowName` has been finished.', Message::ERROR),
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);

        // Act
        $workflowRunner->execute('workflowName', $this->context);
    }

    /**
     * @return void
     */
    public function testExecuteWithErrorCode(): void
    {
        // Arrange
        $this->projectWorkflow
            ->expects($this->once())
            ->method('initializeWorkflow')
            ->willReturn(true);
        $this->projectWorkflow
            ->expects($this->once())
            ->method('getNextEnabledTransitions')
            ->willReturn(['test'], ['test'], []);
        $this->projectWorkflow
            ->expects($this->once())
            ->method('applyTransition');
        $this->projectWorkflow
            ->expects($this->once())
            ->method('getWorkflowMetadata')
            ->willReturn([]);
        $this->container
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->projectWorkflow);
        $this->context
            ->method('addMessage')
            ->with(
                'workflowName',
                new Message('The `workflowName:test` transition is failed, see details above.', Message::ERROR),
            );

        $this->context
            ->method('getExitCode')
            ->willReturn(1);

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);

        // Act
        $workflowRunner->execute('workflowName', $this->context);
    }
}
