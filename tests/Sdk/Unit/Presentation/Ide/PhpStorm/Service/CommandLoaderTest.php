<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Ide\PhpStorm\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\CommandMapperInterface;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoader;
use SprykerSdk\Sdk\Tests\UnitTester;

class CommandLoaderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\Commands\TaskRunFactoryLoader
     */
    protected TaskRunFactoryLoader $commandContainer;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Mapper\CommandMapperInterface
     */
    protected CommandMapperInterface $commandMapper;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoader
     */
    protected CommandLoader $commandLoader;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->commandMapper = $this->createMock(CommandMapperInterface::class);
        $this->taskRepository = $this->createMock(TaskRepositoryInterface::class);
        $this->commandContainer = $this->createMock(TaskRunFactoryLoader::class);
        $this->commandLoader = new CommandLoader(
            $this->commandContainer,
            $this->commandMapper,
            $this->taskRepository,
        );
    }

    /**
     * @return void
     */
    public function testLoad(): void
    {
        // Arrange
        $task = $this->tester->createTask();
        $tasks = [$task];

        $this->taskRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($tasks);

        $symfonyCommand = $this->tester->createSymfonyCommand($task->getId(), $task->getHelp());

        $this->commandContainer
            ->expects($this->exactly(count($tasks)))
            ->method('get')
            ->with($task->getId())
            ->willReturn($symfonyCommand);

        $phpStormCommand = $this->tester->createPhpStormCommand($task->getId(), [], [], $task->getHelp());

        $this->commandMapper
            ->expects($this->exactly(count($tasks)))
            ->method('mapToIdeCommand')
            ->with($symfonyCommand)
            ->willReturn($phpStormCommand);

        // Act
        $result = $this->commandLoader->load();

        // Assert
        $this->assertSame([$phpStormCommand], $result);
    }
}
