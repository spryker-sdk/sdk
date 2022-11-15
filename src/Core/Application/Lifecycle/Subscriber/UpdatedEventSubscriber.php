<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\UpdatedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\TaskLifecycleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatedEventSubscriber extends LifecycleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UpdatedEvent::NAME => 'onUpdatedEvent',
        ];
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Lifecycle\Event\UpdatedEvent $event
     *
     * @return void
     */
    public function onUpdatedEvent(UpdatedEvent $event): void
    {
        $lifecycle = $event->getTask()->getLifecycle();
        if (!$lifecycle instanceof TaskLifecycleInterface) {
            return;
        }

        $updatedEvent = $lifecycle->getUpdatedEventData();
        $context = $this->createContext($updatedEvent, $event->getTask());

        $this->manageFiles($updatedEvent->getFiles(), $context);

        $this->executeCommands($updatedEvent->getCommands(), $context);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface $file
     *
     * @return void
     */
    protected function doManageFile(FileInterface $file): void
    {
        $this->filesystem->dumpFile($file->getPath(), $file->getContent());
    }
}
