<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\RemovedEvent;
use SprykerSdk\SdkContracts\Entity\FileInterface;
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
     * @param \SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\RemovedEvent $event
     *
     * @return void
     */
    public function onRemovedEvent(RemovedEvent $event): void
    {
        /** @var \SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface $lifecycle */
        $lifecycle = $event->getTask()->getLifecycle();
        $removedEventData = $lifecycle->getRemovedEventData();

        $this->manageFiles($removedEventData->getFiles(), $removedEventData->getPlaceholders());

        $this->commandExecutor->execute($removedEventData->getCommands(), $removedEventData->getPlaceholders());
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\FileInterface $file
     *
     * @return void
     */
    protected function doManageFile(FileInterface $file): void
    {
        $this->fileManager->remove($file);
    }
}
