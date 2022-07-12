<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
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
}
