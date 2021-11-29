<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity\Lifecycle;

interface LifecycleInterface extends PersistentLifecycleInterface
{
    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface
     */
    public function getInitializedEvent(): LifecycleEventInterface;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleEventInterface
     */
    public function getUpdatedEvent(): LifecycleEventInterface;
}
