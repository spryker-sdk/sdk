<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Application\Lifecycle\Event\RemovedEvent;
use SprykerSdk\Sdk\Core\Domain\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\PersistentLifecycleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemovedEventSubscriber extends LifecycleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RemovedEvent::NAME => 'onRemovedEvent',
        ];
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Lifecycle\Event\RemovedEvent $event
     *
     * @return void
     */
    public function onRemovedEvent(RemovedEvent $event): void
    {
        $lifecycle = $event->getTask()->getLifecycle();
        if (!$lifecycle instanceof PersistentLifecycleInterface) {
            return;
        }

        $removedEventData = $lifecycle->getRemovedEventData();
        $context = $this->createContext($removedEventData, $event->getTask());

        $this->manageFiles($removedEventData->getFiles(), $context);

        $this->executeCommands($removedEventData->getCommands(), $context);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface $file
     *
     * @return void
     */
    protected function doManageFile(FileInterface $file): void
    {
        $this->filesystem->remove($file->getPath());
    }
}
