<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle;

use SprykerSdk\Sdk\Contracts\Entity\Lifecycle\TaskLifecycleInterface;

class Lifecycle implements TaskLifecycleInterface
{
    protected InitializedEventData $initializedEventData;

    protected UpdatedEventData $updatedEventData;

    protected RemovedEventData $removedEventData;

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData $initializedEventData
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData $updatedEventData
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData $removedEventData
     */
    public function __construct(InitializedEventData $initializedEventData, UpdatedEventData $updatedEventData, RemovedEventData $removedEventData)
    {
        $this->initializedEventData = $initializedEventData;
        $this->updatedEventData = $updatedEventData;
        $this->removedEventData = $removedEventData;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData
     */
    public function getRemovedEventData(): RemovedEventData
    {
        return $this->removedEventData;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData $removedEventData
     *
     * @return $this
     */
    public function setRemovedEventData(RemovedEventData $removedEventData)
    {
        $this->removedEventData = $removedEventData;

        return $this;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData
     */
    public function getInitializedEventData(): InitializedEventData
    {
        return $this->initializedEventData;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData $initializedEventData
     *
     * @return $this
     */
    public function setInitializedEventData(InitializedEventData $initializedEventData)
    {
        $this->initializedEventData = $initializedEventData;

        return $this;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData
     */
    public function getUpdatedEventData(): UpdatedEventData
    {
        return $this->updatedEventData;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData $updatedEventData
     *
     * @return $this
     */
    public function setUpdatedEventData(UpdatedEventData $updatedEventData)
    {
        $this->updatedEventData = $updatedEventData;

        return $this;
    }
}
