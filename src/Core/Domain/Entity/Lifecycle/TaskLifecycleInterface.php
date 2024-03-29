<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;

interface TaskLifecycleInterface extends LifecycleInterface
{
    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface
     */
    public function getInitializedEventData(): LifecycleEventDataInterface;

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface
     */
    public function getUpdatedEventData(): LifecycleEventDataInterface;

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface
     */
    public function getRemovedEventData(): LifecycleEventDataInterface;
}
