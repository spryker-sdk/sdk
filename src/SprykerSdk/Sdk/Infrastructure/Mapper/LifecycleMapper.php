<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle;

class LifecycleMapper implements LifecycleMapperInterface
{
    protected LifecycleEventMapperInterface $lifecycleEventMapper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\LifecycleEventMapperInterface $lifecycleEventMapper
     */
    public function __construct(LifecycleEventMapperInterface $lifecycleEventMapper)
    {
        $this->lifecycleEventMapper = $lifecycleEventMapper;
    }

    public function mapLifecycle(LifecycleInterface $lifecycle): Lifecycle
    {
        $removedEvent = $this->lifecycleEventMapper->mapRemovedEvent($lifecycle->getRemovedEvent());

        return new Lifecycle($removedEvent);
    }
}
