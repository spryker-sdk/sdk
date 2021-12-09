<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Lifecycle\Event;

class InitializedEvent extends LifecycleEvent
{
    /**
     * @var string
     */
    public const NAME = 'sdk.initialized';
}
