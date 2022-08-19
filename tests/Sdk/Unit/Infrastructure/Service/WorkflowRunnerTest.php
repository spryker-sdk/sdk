<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Service\ContextFactory;
use SprykerSdk\Sdk\Core\Application\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WorkflowRunnerTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

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
     * @var \SprykerSdk\Sdk\Core\Application\Service\ContextFactory
     */
    protected ContextFactory $contextFactory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->cliValueReceiver = $this->createMock(CliValueReceiver::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->projectWorkflow = $this->createMock(ProjectWorkflow::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->contextFactory = $this->createMock(ContextFactory::class);

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
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with(
                '<error>Workflow `workflowName` can not be initialized.</error>',
                OutputInterface::VERBOSITY_NORMAL,
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);
        $workflowRunner->setInput(new ArrayInput([]));
        $workflowRunner->setOutput($this->output);

        // Act
        $workflowRunner->execute('workflowName');
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
        $this->output
            ->expects($this->once())
            ->method('writeln')
            ->with(
                '<error>The workflow `workflowName` has been finished.</error>',
                OutputInterface::VERBOSITY_NORMAL,
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);
        $workflowRunner->setInput(new ArrayInput([]));
        $workflowRunner->setOutput($this->output);

        // Act
        $workflowRunner->execute('workflowName');
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
        $this->output
            ->method('writeln')
            ->withConsecutive(
                ['<info>Applying transition `workflowName:test`.</info>', OutputInterface::VERBOSITY_VERY_VERBOSE],
                ['<info>The `workflowName:test` transition finished successfully.</info>', OutputInterface::VERBOSITY_VERY_VERBOSE],
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);
        $workflowRunner->setInput(new ArrayInput([]));
        $workflowRunner->setOutput($this->output);

        // Act
        $workflowRunner->execute('workflowName');
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
        $this->output
            ->method('writeln')
            ->withConsecutive(
                ['<info>Applying transition `workflowName:test`.</info>', OutputInterface::VERBOSITY_VERY_VERBOSE],
                ['<info>The `workflowName:test` transition finished successfully.</info>', OutputInterface::VERBOSITY_VERY_VERBOSE],
                ['<info>Applying transition `workflowName:test`.</info>', OutputInterface::VERBOSITY_VERY_VERBOSE],
                ['<info>The `workflowName:test` transition finished successfully.</info>', OutputInterface::VERBOSITY_VERY_VERBOSE],
                ['<error>The workflow `workflowName` has been finished.</error>', OutputInterface::VERBOSITY_NORMAL],
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);
        $workflowRunner->setInput(new ArrayInput([]));
        $workflowRunner->setOutput($this->output);

        // Act
        $workflowRunner->execute('workflowName');
    }

    /**
     * @return void
     */
    public function testExecuteWithErrorCode(): void
    {
        // Arrange
        $context = new Context();
        $context->setExitCode(1);
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
        $this->output
            ->method('writeln')
            ->withConsecutive(
                ['<info>Applying transition `workflowName:test`.</info>', OutputInterface::VERBOSITY_VERY_VERBOSE],
                ['<error>The `workflowName:test` transition is failed, see details above.</error>', OutputInterface::VERBOSITY_NORMAL],
            );

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container, $this->contextFactory);
        $workflowRunner->setInput(new ArrayInput([]));
        $workflowRunner->setOutput($this->output);

        // Act
        $workflowRunner->execute('workflowName', $context);
    }
}
