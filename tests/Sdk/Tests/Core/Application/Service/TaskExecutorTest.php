<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\ProgressBar\ProgressBarInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;

class TaskExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testExecute(): void
    {
        $code = 0;

        $taskExecutor = new TaskExecutor(
            $this->createTaskRepositoryMock(),
            $this->createCommandExecutorMock(true, $code),
            $this->createEventLoggerMock(),
            $this->createMock(ProgressBarInterface::class),
        );
        $result = $taskExecutor->execute('test');
        $this->assertSame($code, $result);
    }

    /**
     * @return void
     */
    public function testExecuteFailed(): void
    {
        $code = 255;

        $taskExecutor = new TaskExecutor(
            $this->createTaskRepositoryMock(true),
            $this->createCommandExecutorMock(false, $code),
            $this->createEventLoggerMock(),
            $this->createMock(ProgressBarInterface::class),
        );

        $result = $taskExecutor->execute('test');

        $this->assertSame($code, $result);
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface
     */
    protected function createEventLoggerMock(): mixed
    {
        $eventLogger = $this->createMock(EventLoggerInterface::class);

        return $eventLogger;
    }

    /**
     * @param bool|false $hasStopOnError
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Entity\TaskInterface
     */
    protected function createTaskMock($hasStopOnError = false): mixed
    {
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->expects($this->once())
            ->method('getPlaceholders')
            ->willReturn([$this->createPlaceholderMock()]);

        $taskMock->expects($this->exactly(1))
            ->method('getCommands')
            ->willReturnCallback(function () use ($hasStopOnError): array {
                return [$this->createCommandMock(), $this->createCommandMock($hasStopOnError)];
            });

        return $taskMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholderMock(): mixed
    {
        $placeholderMock = $this->createMock(PlaceholderInterface::class);

        return $placeholderMock;
    }

    /**
     * @param bool|false $hasStopOnError
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Entity\CommandInterface
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
     * @param bool $isSuccessful
     * @param int $code
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected function createCommandExecutorMock(bool $isSuccessful, int $code): CommandExecutorInterface
    {
        $commandExecutor = $this->createMock(CommandExecutorInterface::class);
        $commandExecutor
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new CommandResponse($isSuccessful, $code));

        return $commandExecutor;
    }
}
