<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity\Lifecycle;

interface PersistentLifecycleInterface extends LifecycleInterface
{
    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventDataInterface
     */
    public function getRemovedEventData(): LifecycleEventDataInterface;
}
