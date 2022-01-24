<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\Lifecycle\Subscriber;

use Codeception\Test\Unit;
use Doctrine\Common\Collections\ArrayCollection;
use SprykerSdk\Sdk\Core\Appplication\Dependency\CommandExecutorInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\RemovedEvent as SubscriberRemovedEvent;
use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Subscriber\RemovedEventSubscriber;
use SprykerSdk\Sdk\Core\Appplication\Service\PlaceholderResolver;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle as InfrastructureLifecycle;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent as InfrastructureRemovedEvent;
use SprykerSdk\Sdk\Tests\UnitTester;

class RemovedEventSubscriberTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Lifecycle\Subscriber\RemovedEventSubscriber
     */
    protected RemovedEventSubscriber $subscriber;

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

        $this->subscriber = new RemovedEventSubscriber(
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
            SubscriberRemovedEvent::NAME => 'onRemovedEvent',
        ];

        // Act
        $result = RemovedEventSubscriber::getSubscribedEvents();

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals($events, $result);
    }

    /**
     * @return void
     */
    public function testOnRemovedEventShouldRemoveFilesAndExecuteCommands(): void
    {
        // Arrange
        $command = $this->tester->createInfrastructureCommand();
        $file = $this->tester->createInfrastructureFile('/foo/%path%', 'content');

        $placeholderConfig = [
            'name' => 'path',
            'defaultValue' => 'bar',
            'type' => 'path',
        ];

        $placeholder = $this->tester->createInfrastructurePlaceholder(
            'path',
            'STATIC',
            false,
            $placeholderConfig,
        );

        $files = [$file];
        $commands = [$command];
        $placeholders = [$placeholder];

        $removedEvent = new InfrastructureRemovedEvent();
        $removedEvent->setPlaceholders(new ArrayCollection($placeholders));
        $removedEvent->setFiles(new ArrayCollection($files));
        $removedEvent->setCommands(new ArrayCollection($commands));

        $lifecycle = new InfrastructureLifecycle($removedEvent);

        $task = $this->tester->createInfrastructureTask($lifecycle);
        $event = new SubscriberRemovedEvent($task);

        $this->fileManager
            ->expects($this->exactly(count($files)))
            ->method('remove');

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
        $this->subscriber->onRemovedEvent($event);
    }

    /**
     * @return void
     */
    public function testOnRemovedEventWithIncorrectLifecycleShouldDoNothing(): void
    {
        // Arrange
        $lifecycle = new Lifecycle(new InitializedEventData(), new UpdatedEventData(), new RemovedEventData());

        $task = $this->tester->createInfrastructureTask($lifecycle);
        $event = new SubscriberRemovedEvent($task);

        $this->fileManager
            ->expects($this->never())
            ->method('remove');

        $this->commandExecutor
            ->expects($this->never())
            ->method('execute');

        $this->placeholderResolver
            ->expects($this->never())
            ->method('resolvePlaceholders');

        // Act
        $this->subscriber->onRemovedEvent($event);
    }
}
