<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver;
use SprykerSdk\Sdk\Infrastructure\Service\WorkflowRunner;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WorkflowRunnerTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Infrastructure\Service\CliValueReceiver
     */
    protected CliValueReceiver $cliValueReceiver;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected ProjectWorkflow $projectWorkflow;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->cliValueReceiver = $this->createMock(CliValueReceiver::class);
        $this->container = $this->createMock(ContainerInterface::class);
        $this->projectWorkflow = $this->createMock(ProjectWorkflow::class);

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

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container);

        // Act
        $messages = $workflowRunner->execute('workflowName')->getMessages();

        // Assert
        $message = current($messages);
        $messageKey = array_key_first($messages);
        $this->assertSame('workflowName_init', $messageKey);
        $this->assertSame('Workflow `workflowName` can not be initialized.', $message->getMessage());
        $this->assertSame(MessageInterface::ERROR, $message->getVerbosity());
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

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container);

        // Act
        $messages = $workflowRunner->execute('workflowName')->getMessages();

        // Assert
        $message = current($messages);
        $messageKey = array_key_first($messages);
        $this->assertSame('workflowName__start', $messageKey);
        $this->assertSame('The workflow `workflowName` has been finished.', $message->getMessage());
        $this->assertSame(MessageInterface::ERROR, $message->getVerbosity());
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

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container);

        // Act
        $messages = $workflowRunner->execute('workflowName')->getMessages();

        // Assert
        $this->assertSame(
            [
                'workflowName_test_apply' => 'Running transition `workflowName:test` ...',
                'workflowName_test_done' => 'The `workflowName:test` transition finished successfully.',
            ],
            array_map(function ($message) {
                return $message->getMessage();
            }, $messages),
        );
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

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container);

        // Act
        $messages = $workflowRunner->execute('workflowName')->getMessages();

        // Assert
        $this->assertSame(
            [
                'workflowName_test_apply' => 'Running transition `workflowName:test` ...',
                'workflowName_test_done' => 'The `workflowName:test` transition finished successfully.',
                'workflowName__start' => 'The workflow `workflowName` has been finished.',
            ],
            array_map(function ($message) {
                return $message->getMessage();
            }, $messages),
        );
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

        $workflowRunner = new WorkflowRunner($this->cliValueReceiver, $this->container);

        // Act
        $messages = $workflowRunner->execute('workflowName', $context)->getMessages();

        // Assert
        $this->assertSame(
            [
                'workflowName_test_apply' => 'Running transition `workflowName:test` ...',
                'workflowName_test_fail' => 'The `workflowName:test` transition is failed, see details above.',
            ],
            array_map(function ($message) {
                return $message->getMessage();
            }, $messages),
        );
    }
}
