<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Events;

use SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface;

class TaskExecutedEvent extends Event
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface $command
     * @param bool $isSuccessful
     */
    public function __construct(
        TaskInterface $task,
        CommandInterface $command,
        bool $isSuccessful
    ) {
        parent::__construct(
            $task->getId(),
            $command->getType(),
            'executed',
            $isSuccessful,
            'user',
            $task->getId(),
        );
    }
}
