<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Lifecycle\Event;

use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;
use Symfony\Contracts\EventDispatcher\Event;

abstract class LifecycleEvent extends Event
{
    public function __construct(protected TaskInterface $task)
    {
    }
}
