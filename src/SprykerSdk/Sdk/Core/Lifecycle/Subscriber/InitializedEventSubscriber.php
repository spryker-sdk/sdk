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
     * @return array<string, mixed>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            InitializedEvent::NAME => 'onInitializedEvent',
        ];
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent $event
     *
     * @return void
     */
    public function onInitializedEvent(InitializedEvent $event): void
    {
        /** @var \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface $lifecycle */
        $lifecycle = $event->getTask()->getLifecycle();
        $initialized = $lifecycle->getInitializedEvent();

        $this->manageFiles($initialized->getFiles(), $initialized->getPlaceholders());

        $this->commandExecutor->execute($initialized->getCommands(), $initialized->getPlaceholders());
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
}
