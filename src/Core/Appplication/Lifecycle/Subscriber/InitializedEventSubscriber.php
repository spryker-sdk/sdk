<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event\InitializedEvent;
use SprykerSdk\SdkContracts\Entity\FileInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;
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
        $lifecycle = $event->getTask()->getLifecycle();
        if (!$lifecycle instanceof TaskLifecycleInterface) {
            return;
        }

        $initializedEvent = $lifecycle->getInitializedEventData();
        $context = $this->createContext($initializedEvent, $event->getTask());

        $this->manageFiles($initializedEvent->getFiles(), $context);

        $this->executeCommands($initializedEvent->getCommands(), $context);
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
