<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Enum;

final class Lifecycle
{
    /**
     * Event name for initializing task.
     *
     * @var string
     */
    public const EVENT_INITIALIZED = 'INITIALIZED';

    /**
     * Event name for updating task.
     *
     * @var string
     */
    public const EVENT_UPDATED = 'UPDATED';

    /**
     * Event name for removing task.
     *
     * @var string
     */
    public const EVENT_REMOVED = 'REMOVED';
}
