<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group TaskExecutorTest
 */
class TaskExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testExecute(): void
    {
        // Arrange
        $context = new Context();
        $context->setExitCode(ContextInterface::SUCCESS_EXIT_CODE);

        $taskExecutor = new TaskExecutor(
            $this->createPlaceholderResolverMock(),
            $this->createTaskRepositoryMock(),
            $this->createCommandExecutorMock($context),
            $this->createViolationConverterGeneratorMock(),
            $this->createProjectWorkflowMock(),
        );

        // Act
        $result = $taskExecutor->execute('test', $context);

        // Assert
        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return void
     */
    public function testExecuteFailed(): void
    {
        // Arrange
        $context = new Context();
        $context->setExitCode(ContextInterface::FAILURE_EXIT_CODE);

        $taskExecutor = new TaskExecutor(
            $this->createPlaceholderResolverMock(),
            $this->createTaskRepositoryMock(),
            $this->createCommandExecutorMock($context),
            $this->createViolationConverterGeneratorMock(),
            $this->createProjectWorkflowMock(),
        );

        // Act
        $result = $taskExecutor->execute('test', $context);

        // Assert
        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Service\ProjectWorkflow
     */
    protected function createProjectWorkflowMock(): ProjectWorkflow
    {
        $projectWorkflow = $this->createMock(ProjectWorkflow::class);
        $projectWorkflow->expects($this->once())
            ->method('initWorkflow')
            ->willReturn(true);
        $projectWorkflow->expects($this->once())
            ->method('applyTransaction');

        return $projectWorkflow;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator
     */
    protected function createViolationConverterGeneratorMock(): ViolationReportGenerator
    {
        $violationReportGenerator = $this->createMock(ViolationReportGenerator::class);

        return $violationReportGenerator;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected function createPlaceholderResolverMock(): PlaceholderResolver
    {
        $placeholderResolver = $this->createMock(PlaceholderResolver::class);
        $placeholderResolver->expects($this->once())
            ->method('resolve')
            ->willReturn('string');

        return $placeholderResolver;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Logger\EventLoggerInterface
     */
    protected function createEventLoggerMock(): EventLoggerInterface
    {
        $eventLogger = $this->createMock(EventLoggerInterface::class);

        return $eventLogger;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected function createTaskRepositoryMock(): TaskRepositoryInterface
    {
        $placeholderResolver = $this->createMock(TaskRepositoryInterface::class);
        $placeholderResolver->expects($this->once())
            ->method('findById')
            ->willReturn($this->createTaskMock());

        return $placeholderResolver;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(): TaskInterface
    {
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->expects($this->once())
            ->method('getPlaceholders')
            ->willReturn([$this->createPlaceholderMock()]);

        return $taskMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholderMock(): PlaceholderInterface
    {
        $placeholderMock = $this->createMock(PlaceholderInterface::class);

        return $placeholderMock;
    }

    /**
     * @param bool|false $hasStopOnError
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(bool $hasStopOnError = false): CommandInterface
    {
        $placeholderResolver = $this->createMock(CommandInterface::class);
        $placeholderResolver
            ->method('hasStopOnError')
            ->willReturn($hasStopOnError);

        return $placeholderResolver;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected function createCommandExecutorMock(): CommandExecutorInterface
    {
        $commandExecutor = $this->createMock(CommandExecutorInterface::class);

        return $commandExecutor;
    }
}
