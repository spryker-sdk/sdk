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
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Logger\EventLoggerInterface;

class TaskExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testExecute(): void
    {
        $context = new Context();
        $context->setExitCode(ContextInterface::SUCCESS_EXIT_CODE);

        $taskExecutor = new TaskExecutor(
            $this->createPlaceholderResolverMock(),
            $this->createTaskRepositoryMock(),
            $this->createCommandExecutorMock($context),
            $this->createViolationConverterResolverMock(),
        );
        $result = $taskExecutor->execute('test', $context);
        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return void
     */
    public function testExecuteFailed(): void
    {
        $context = new Context();
        $context->setExitCode(ContextInterface::FAILURE_EXIT_CODE);

        $taskExecutor = new TaskExecutor(
            $this->createPlaceholderResolverMock(),
            $this->createTaskRepositoryMock(),
            $this->createCommandExecutorMock($context),
            $this->createViolationConverterResolverMock(),
        );

        $result = $taskExecutor->execute('test', $context);

        $this->assertSame($context->getExitCode(), $result->getExitCode());
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver
     */
    protected function createViolationConverterResolverMock(): ViolationConverterResolver
    {
        $violationConverterResolver = $this->createMock(ViolationConverterResolver::class);

        return $violationConverterResolver;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected function createPlaceholderResolverMock(): mixed
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
    protected function createEventLoggerMock(): mixed
    {
        $eventLogger = $this->createMock(EventLoggerInterface::class);

        return $eventLogger;
    }

    /**
     * @param bool|false $hasStopOnError
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected function createTaskRepositoryMock($hasStopOnError = false): mixed
    {
        $placeholderResolver = $this->createMock(TaskRepositoryInterface::class);
        $placeholderResolver->expects($this->once())
            ->method('findById')
            ->willReturn($this->createTaskMock($hasStopOnError));

        return $placeholderResolver;
    }

    /**
     * @param bool|false $hasStopOnError
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock($hasStopOnError = false): mixed
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
    protected function createPlaceholderMock(): mixed
    {
        $placeholderMock = $this->createMock(PlaceholderInterface::class);

        return $placeholderMock;
    }

    /**
     * @param bool|false $hasStopOnError
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(bool $hasStopOnError = false): mixed
    {
        $placeholderResolver = $this->createMock(CommandInterface::class);
        $placeholderResolver
            ->method('hasStopOnError')
            ->willReturn($hasStopOnError);

        return $placeholderResolver;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected function createCommandExecutorMock(ContextInterface $context): CommandExecutorInterface
    {
        $commandExecutor = $this->createMock(CommandExecutorInterface::class);

        return $commandExecutor;
    }
}
