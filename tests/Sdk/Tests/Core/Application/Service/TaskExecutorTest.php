<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface;
use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Logger\EventLoggerInterface;
use SprykerSdk\Sdk\Contracts\ProgressBar\ProgressBarInterface;
use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\TaskExecutor;
use SprykerSdk\Sdk\Infrastructure\Exception\CommandRunnerException;

class TaskExecutorTest extends Unit
{
    /**
     * @return void
     */
    public function testExecute(): void
    {
        $taskExecutor = new TaskExecutor(
            [$this->createCommandRunnerMock(), $this->createCommandRunnerMock(false)],
            $this->createPlaceholderResolverMock(),
            $this->createTaskRepositoryMock(),
            $this->createEventLoggerMock(),
            $this->createMock(ProgressBarInterface::class),
        );
        $result = $taskExecutor->execute('test');
        $this->assertSame(0, $result);
    }

    /**
     * @return void
     */
    public function testExecuteFailed(): void
    {
        $taskExecutor = new TaskExecutor(
            [$this->createCommandRunnerMock(), $this->createCommandRunnerMock(false)],
            $this->createPlaceholderResolverMock(),
            $this->createTaskRepositoryMock(true),
            $this->createEventLoggerMock(),
            $this->createMock(ProgressBarInterface::class),
        );
        $this->expectException(CommandRunnerException::class);

        $taskExecutor->execute('test');
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
        $placeholderResolver = $this->createMock(EventLoggerInterface::class);
        $placeholderResolver->expects($this->exactly(4))
            ->method('logEvent');

        return $placeholderResolver;
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
    public function createTaskMock($hasStopOnError = false): mixed
    {
        $placeholderResolver = $this->createMock(TaskInterface::class);
        $placeholderResolver->expects($this->once())
            ->method('getPlaceholders')
            ->willReturnCallback(function (): array {
                return [$this->createPlaceholderMock()];
            });
        $placeholderResolver->expects($this->exactly(2))
            ->method('getCommands')
            ->willReturnCallback(function () use ($hasStopOnError): array {
                return [$this->createCommandMock(), $this->createCommandMock($hasStopOnError)];
            });

        return $placeholderResolver;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface
     */
    public function createPlaceholderMock(): mixed
    {
        $placeholderResolver = $this->createMock(PlaceholderInterface::class);
        $placeholderResolver->expects($this->once())
            ->method('getName')
            ->willReturn('name');

        return $placeholderResolver;
    }

    /**
     * @param bool|false $hasStopOnError
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\Entity\CommandInterface
     */
    public function createCommandMock(bool $hasStopOnError = false): mixed
    {
        $placeholderResolver = $this->createMock(CommandInterface::class);
        $placeholderResolver
            ->method('hasStopOnError')
            ->willReturn($hasStopOnError);

        return $placeholderResolver;
    }

    /**
     * @param bool|true $isSuccessful
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerSdk\Sdk\Contracts\CommandRunner\CommandRunnerInterface
     */
    public function createCommandRunnerMock($isSuccessful = true): mixed
    {
        $placeholderResolver = $this->createMock(CommandRunnerInterface::class);
        $placeholderResolver->expects($this->exactly(4))
            ->method('canHandle')
            ->willReturn(true);
        $placeholderResolver->expects($this->exactly(2))
            ->method('execute')
            ->willReturnCallback(function (CommandInterface $command, array $resolvedValues) use ($isSuccessful): CommandResponse {
                return new CommandResponse($isSuccessful);
            });

        return $placeholderResolver;
    }
}
