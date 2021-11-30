<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity\Lifecycle;

interface PersistentLifecycleInterface
{
    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface
     */
    public function getRemovedEvent(): LifecycleEventInterface;
}
