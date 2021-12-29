<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleEventDataInterface;

interface RemovedEventMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleEventDataInterface $lifecycleEventData
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function mapRemovedEvent(LifecycleEventDataInterface $lifecycleEventData): RemovedEvent;
}
