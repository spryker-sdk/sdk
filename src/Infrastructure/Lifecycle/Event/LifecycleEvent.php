<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Lifecycle\Event;

use SprykerSdk\SdkContracts\Entity\TaskInterface;
use Symfony\Contracts\EventDispatcher\Event;

abstract class LifecycleEvent extends Event
{
    protected TaskInterface $task;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getTask(): TaskInterface
    {
        return $this->task;
    }
}
