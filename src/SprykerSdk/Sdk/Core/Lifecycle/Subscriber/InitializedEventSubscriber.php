<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitializedEventSubscriber implements EventSubscriberInterface
{
    public function onInitializedEvent(InitializedEvent $event): void
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InitializedEvent::NAME => 'onInitializedEvent',
        ];
    }
}
