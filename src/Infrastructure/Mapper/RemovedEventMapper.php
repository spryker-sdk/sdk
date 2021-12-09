<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventDataInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;

class RemovedEventMapper implements RemovedEventMapperInterface
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventDataInterface $lifecycleEvent
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function mapRemovedEvent(LifecycleEventDataInterface $lifecycleEventData): RemovedEvent
    {
        $removedEvent = new RemovedEvent();
        $removedEvent = $this->mapCommands($lifecycleEvent->getCommands(), $removedEvent);
        $removedEvent = $this->mapPlaceholders($lifecycleEvent->getPlaceholders(), $removedEvent);
        $removedEvent = $this->mapFiles($lifecycleEvent->getFiles(), $removedEvent);

        return $removedEvent;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $event
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
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $placeholders
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $event
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
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\FileInterface> $files
     * @param \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent $event
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
