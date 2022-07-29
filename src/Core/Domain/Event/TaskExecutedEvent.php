<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Event;

use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskExecutedEvent extends Event
{
    /**
     * @var string
     */
    public const EVENT_NAME = 'executed';

    /**
     * @var string
     */
    public const TRIGGERED_BY_USER = 'user';

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
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
            static::EVENT_NAME,
            $isSuccessful,
            static::TRIGGERED_BY_USER,
            $task->getId(),
        );
    }
}
