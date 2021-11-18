<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Events;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;

class TaskEvent extends Event
{
    public function __construct(
        Task $task,
        Command $command,
        bool $isSuccessful
    ){
        parent::__construct(
            $task->id,
            $command->type,
            'executed',
            $isSuccessful,
            'user',
            $task->id
        );
    }
}