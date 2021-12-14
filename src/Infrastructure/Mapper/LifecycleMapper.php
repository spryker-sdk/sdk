<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\Lifecycle;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface;

class LifecycleMapper implements LifecycleMapperInterface
{
    protected RemovedEventMapperInterface $removedEventMapper;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\RemovedEventMapperInterface $removedEventMapper
     */
    public function __construct(RemovedEventMapperInterface $removedEventMapper)
    {
        $this->removedEventMapper = $removedEventMapper;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface $lifecycle
     *
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\PersistentLifecycleInterface
     */
    public function mapLifecycle(LifecycleInterface $lifecycle): PersistentLifecycleInterface
    {
        $removedEvent = $this->removedEventMapper->mapRemovedEvent($lifecycle->getRemovedEventData());

        return new Lifecycle($removedEvent);
    }
}
