<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use Doctrine\DBAL\Exception\TableNotFoundException;
use InvalidArgumentException;
use SprykerSdk\Sdk\Infrastructure\Service\ErrorCommandListener;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Service
 * @group ErrorCommandListenerTest
 * Add your own group annotations below this line
 */
class ErrorCommandListenerTest extends Unit
{
    /**
     * @var int
     */
    protected const DEFAULT_ERROR_CODE = 3;

    /**
     * @var int
     */
    protected const SUCCESS_ERROR_CODE = 0;

    /**
     * @return void
     */
    public function testSkipProcessingWhenErrorIsNotTableNotFoundException(): void
    {
        //Arrange
        $event = new ConsoleErrorEvent($this->createInputMock(), $this->createOutputMock(true), new InvalidArgumentException());
        $event->setExitCode(static::DEFAULT_ERROR_CODE);
        $eventListener = new ErrorCommandListener(true);

        //Act
        $eventListener->handle($event);

        //Assert
        $this->assertSame(static::DEFAULT_ERROR_CODE, $event->getExitCode());
        $this->assertInstanceOf(InvalidArgumentException::class, $event->getError());
    }

    /**
     * @return void
     */
    public function testSkipProcessingWhenDebugTrue(): void
    {
        //Arrange
        $event = new ConsoleErrorEvent($this->createInputMock(), $this->createOutputMock(true), $this->createTableNotFoundExceptionMock());
        $event->setExitCode(static::DEFAULT_ERROR_CODE);
        $eventListener = new ErrorCommandListener(true);

        //Act
        $eventListener->handle($event);

        //Assert
        $this->assertSame(static::DEFAULT_ERROR_CODE, $event->getExitCode());
        $this->assertInstanceOf(TableNotFoundException::class, $event->getError());
    }

    /**
     * @dataProvider requiredDebugSetting
     *
     * @param bool $verbosity
     * @param bool $debug
     *
     * @return void
     */
    public function testProcessingWhenErrorIsTableNotFoundException(bool $verbosity, bool $debug): void
    {
        //Arrange
        $tableNotFoundExceptionMock = $this->createTableNotFoundExceptionMock();
        $event = new ConsoleErrorEvent($this->createInputMock(), $this->createOutputMock($verbosity), $tableNotFoundExceptionMock);
        $event->setExitCode(static::DEFAULT_ERROR_CODE);
        $eventListener = new ErrorCommandListener($debug);

        //Act
        $eventListener->handle($event);

        //Assert
        $this->assertSame(static::SUCCESS_ERROR_CODE, $event->getExitCode());
        $this->assertInstanceOf(get_class($tableNotFoundExceptionMock), $event->getError());
    }

    /**
     * @return array<array<string>>
     */
    public function requiredDebugSetting(): array
    {
        return [
            [false, true],
            [true, false],
        ];
    }

    /**
     * @return \Symfony\Component\Console\Input\InputInterface
     */
    protected function createInputMock(): InputInterface
    {
        return $this->createMock(InputInterface::class);
    }

    /**
     * @param bool $isDebug
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Symfony\Component\Console\Output\OutputInterface
     */
    protected function createOutputMock(bool $isDebug = false): OutputInterface
    {
        $outputMock = $this->createMock(OutputInterface::class);
        $outputMock->method('isDebug')->willReturn($isDebug);

        return $outputMock;
    }

    /**
     * @return \Doctrine\DBAL\Exception\TableNotFoundException
     */
    protected function createTableNotFoundExceptionMock(): TableNotFoundException
    {
        return $this->createMock(TableNotFoundException::class);
    }
}
