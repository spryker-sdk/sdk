<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Lifecycle\Event\RemovedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RemovedEventSubscriber implements EventSubscriberInterface
{
    public function onRemovedEvent(RemovedEvent $event): void
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RemovedEvent::NAME => 'onRemovedEvent',
        ];
    }
}
