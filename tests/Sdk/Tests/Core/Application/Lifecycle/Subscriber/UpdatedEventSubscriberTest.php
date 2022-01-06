<?php


/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\Lifecycle\Subscriber;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Subscriber\UpdatedEventSubscriber;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle as InfrastructureLifecycle;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;
use SprykerSdk\Sdk\Tests\UnitTester;

class UpdatedEventSubscriberTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Lifecycle\Subscriber\UpdatedEventSubscriber
     */
    protected UpdatedEventSubscriber $subscriber;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface
     */
    protected FileManagerInterface $fileManager;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver
     */
    protected PlaceholderResolver $placeholderResolver;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface
     */
    protected CommandExecutorInterface $commandExecutor;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->fileManager = $this->createMock(FileManagerInterface::class);
        $this->placeholderResolver = $this->createMock(PlaceholderResolver::class);
        $this->commandExecutor = $this->createMock(CommandExecutorInterface::class);

        $this->subscriber = new UpdatedEventSubscriber(
            $this->fileManager,
            $this->placeholderResolver,
            $this->commandExecutor,
        );
    }

    /**
     * @return void
     */
    public function testGetSubscribedEventsShouldReturnMap(): void
    {
        // Arrange
        $events = [
            UpdatedEvent::NAME => 'onUpdatedEvent',
        ];

        // Act
        $result = UpdatedEventSubscriber::getSubscribedEvents();

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals($events, $result);
    }

    /**
     * @return void
     */
    public function testOnUpdatedEventShouldCreateFilesAndExecuteCommands(): void
    {
        // Arrange
        $command = $this->tester->createCommand();
        $file = $this->tester->createFile('/foo/%path%', 'content');

        $placeholderConfig = [
            'name' => 'path',
            'defaultValue' => 'bar',
            'type' => 'path',
        ];

        $placeholder = $this->tester->createPlaceholder(
            'path',
            'STATIC',
            false,
            $placeholderConfig,
        );

        $files = [$file];
        $commands = [$command];
        $placeholders = [$placeholder];

        $lifecycle = new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData($commands, $placeholders, $files),
            new RemovedEventData(),
        );

        $task = $this->tester->createTask($lifecycle);
        $event = new UpdatedEvent($task);

        $this->fileManager
            ->expects($this->exactly(count($files)))
            ->method('create');

        $this->commandExecutor
            ->expects($this->exactly(count($commands)))
            ->method('execute');

        $this->placeholderResolver
            ->expects($this->once())
            ->method('resolvePlaceholders')
            ->willReturn([
                $placeholderConfig['name'] => $placeholderConfig['defaultValue'],
            ]);

        // Act
        $this->subscriber->onUpdatedEvent($event);
    }

    /**
     * @return void
     */
    public function testOnUpdatedEventWithIncorrectLifecycleShouldDoNothing(): void
    {
        // Arrange
        $lifecycle = new InfrastructureLifecycle(new RemovedEvent());

        $task = $this->tester->createTask($lifecycle);
        $event = new UpdatedEvent($task);

        $this->fileManager
            ->expects($this->never())
            ->method('create');

        $this->commandExecutor
            ->expects($this->never())
            ->method('execute');

        $this->placeholderResolver
            ->expects($this->never())
            ->method('resolvePlaceholders');

        // Act
        $this->subscriber->onUpdatedEvent($event);
    }
}
