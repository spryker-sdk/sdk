<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;

class LifecycleEventMapper implements LifecycleEventMapperInterface
{
    protected PlaceholderMapperInterface $placeholderMapper;

    protected CommandMapperInterface $commandMapper;

    protected FileMapperInterface $fileMapper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\PlaceholderMapperInterface $placeholderMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\CommandMapperInterface $commandMapper
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\FileMapperInterface $fileMapper
     */
    public function __construct(
        PlaceholderMapperInterface $placeholderMapper,
        CommandMapperInterface $commandMapper,
        FileMapperInterface $fileMapper
    ) {
        $this->placeholderMapper = $placeholderMapper;
        $this->commandMapper = $commandMapper;
        $this->fileMapper = $fileMapper;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface $lifecycleEvent
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function mapRemovedEvent(LifecycleEventInterface $lifecycleEvent): RemovedEvent
    {
        $removedEvent = new RemovedEvent();
        $removedEvent = $this->mapCommands($lifecycleEvent->getCommands(), $removedEvent);
        $removedEvent = $this->mapPlaceholders($lifecycleEvent->getPlaceholders(), $removedEvent);
        $removedEvent = $this->mapFiles($lifecycleEvent->getFiles(), $removedEvent);

        return $removedEvent;
    }

    public function updateRemovedEvent(LifecycleEventInterface $lifecycleEvent, RemovedEvent $removedEvent): RemovedEvent
    {
        $removedEvent->setFiles(new ArrayCollection());
        $removedEvent->setPlaceholders(new ArrayCollection());
        $removedEvent->setCommands(new ArrayCollection());

        $removedEvent = $this->mapCommands($lifecycleEvent->getCommands(), $removedEvent);
        $removedEvent = $this->mapPlaceholders($lifecycleEvent->getPlaceholders(), $removedEvent);
        $removedEvent = $this->mapFiles($lifecycleEvent->getFiles(), $removedEvent);

        return $removedEvent;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface[] $commands
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    protected function mapCommands(array $commands, RemovedEvent $event): RemovedEvent
    {
        foreach ($commands as $command) {
            $commandEntity = $this->commandMapper->mapCommand($command);

            $event->addCommand($commandEntity);
        }

        return $event;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface[] $placeholders
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    protected function mapPlaceholders(array $placeholders, RemovedEvent $event): RemovedEvent
    {
        foreach ($placeholders as $placeholder) {
            $placeholderEntity = $this->placeholderMapper->mapPlaceholder($placeholder);

            $event->addPlaceholder($placeholderEntity);
        }

        return $event;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface[] $files
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $task
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    protected function mapFiles(array $files, RemovedEvent $event): RemovedEvent
    {
        foreach ($files as $file) {
            $fileEntity = $this->fileMapper->mapFile($file);

            $event->addFile($fileEntity);
        }

        return $event;
    }
}
