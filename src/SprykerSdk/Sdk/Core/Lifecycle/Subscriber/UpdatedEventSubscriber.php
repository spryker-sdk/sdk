<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Contracts\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\UpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatedEventSubscriber extends LifecycleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Lifecycle\Event\UpdatedEvent $event
     *
     * @return void
     */
    public function onUpdatedEvent(UpdatedEvent $event): void
    {
        /** @var \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface $lifecycle */
        $lifecycle = $event->getTask()->getLifecycle();
        $updated = $lifecycle->getUpdatedEvent();

        $resolvedPlaceholders = $this->resolvePlaceholders($updated->getPlaceholders());

        $this->manageFiles($updated->getFiles(), $resolvedPlaceholders);
        $this->executeCommands($updated->getCommands(), $resolvedPlaceholders);
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

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UpdatedEvent::NAME => 'onUpdatedEvent',
        ];
    }
}
