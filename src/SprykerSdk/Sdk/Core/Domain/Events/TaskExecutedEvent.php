<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Events;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;

class TaskExecutedEvent extends Event
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     * @param bool $isSuccessful
     */
    public function __construct(
        TaskInterface    $task,
        CommandInterface $command,
        bool             $isSuccessful
    ){
        parent::__construct(
            $task->getId(),
            $command->getType(),
            'executed',
            $isSuccessful,
            'user',
            $task->getId()
        );
    }
}
