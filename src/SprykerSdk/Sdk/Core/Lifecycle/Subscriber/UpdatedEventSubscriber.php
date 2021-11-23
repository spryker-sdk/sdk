<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Subscriber;

use SprykerSdk\Sdk\Core\Lifecycle\Event\UpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatedEventSubscriber implements EventSubscriberInterface
{
    public function onUpdatedEvent(UpdatedEvent $event): void
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UpdatedEvent::NAME => 'onUpdatedEvent',
        ];
    }
}
