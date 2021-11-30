<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Contracts\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\RemovedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemovedEventSubscriber extends LifecycleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Lifecycle\Event\RemovedEvent $event
     *
     * @return void
     */
    public function onRemovedEvent(RemovedEvent $event): void
    {
        $removed = $event->getTask()->getLifecycle()->getRemovedEvent();

        $resolvedPlaceholders = $this->resolvePlaceholders($removed->getPlaceholders());

        $this->manageFiles($removed->getFiles(), $resolvedPlaceholders);
        $this->executeCommands($removed->getCommands(), $resolvedPlaceholders);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface $file
     *
     * @return void
     */
    protected function doManageFile(FileInterface $file): void
    {
        $this->fileManager->remove($file);
    }

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RemovedEvent::NAME => 'onRemovedEvent',
        ];
    }
}
