<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface;
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

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\Lifecycle\PersistentLifecycleInterface $lifecycle
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle
     */
    public function mapLifecycle(PersistentLifecycleInterface $lifecycle): Lifecycle
    {
        $removedEvent = $this->lifecycleEventMapper->mapRemovedEvent($lifecycle->getRemovedEvent());

        return new Lifecycle($removedEvent);
    }
}
