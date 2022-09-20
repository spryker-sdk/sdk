<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent;

interface RemovedEventMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface $lifecycleEventData
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\RemovedEvent
     */
    public function mapRemovedEvent(LifecycleEventDataInterface $lifecycleEventData): RemovedEvent;
}
