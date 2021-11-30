<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Contracts\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitializedEventSubscriber extends LifecycleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent $event
     *
     * @return void
     */
    public function onInitializedEvent(InitializedEvent $event): void
    {
        $initialized = $event->getTask()->getLifecycle()?->getInitializedEvent();

        $resolvedPlaceholders = $this->resolvePlaceholders($initialized->getPlaceholders());

        $this->manageFiles($initialized->getFiles(), $resolvedPlaceholders);
        $this->executeCommands($initialized->getCommands(), $resolvedPlaceholders);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface $file
     *
     * @return void
     */
    protected function doManageFile(FileInterface $file): void
    {
        $this->fileManager->create($file);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InitializedEvent::NAME => 'onInitializedEvent',
        ];
    }
}
