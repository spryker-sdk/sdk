<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\InitializedEvent;
use SprykerSdk\SdkContracts\Entity\FileInterface;
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
     * @param \SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\InitializedEvent $event
     *
     * @return void
     */
    public function onInitializedEvent(InitializedEvent $event): void
    {
        /** @var \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface $lifecycle */
        $lifecycle = $event->getTask()->getLifecycle();
        $initializedEvent = $lifecycle->getInitializedEventData();
        $context = $this->createContext($initializedEvent);

        $this->manageFiles($initializedEvent->getFiles(), $context);

        $this->executeCommands($initializedEvent->getCommands(), $context, $event->getTask());
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\FileInterface $file
     *
     * @return void
     */
    protected function doManageFile(FileInterface $file): void
    {
        $this->fileManager->create($file);
    }
}
