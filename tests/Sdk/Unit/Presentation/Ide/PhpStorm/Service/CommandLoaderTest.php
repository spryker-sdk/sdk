<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Ide\PhpStorm\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Presentation\Console\Command\TaskRunFactoryLoader;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoader;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Presentation
 * @group Ide
 * @group PhpStorm
 * @group Service
 * @group CommandLoaderTest
 * Add your own group annotations below this line
 */
class CommandLoaderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\Command\TaskRunFactoryLoader
     */
    protected TaskRunFactoryLoader $commandContainer;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
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
        $this->taskRepository = $this->createMock(TaskRepositoryInterface::class);
        $this->commandContainer = $this->createMock(TaskRunFactoryLoader::class);
        $this->commandLoader = new CommandLoader(
            [],
            $this->commandContainer,
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

        // Act
        $result = $this->commandLoader->load();

        // Assert
        $this->assertEquals([$phpStormCommand], $result);
    }
}
