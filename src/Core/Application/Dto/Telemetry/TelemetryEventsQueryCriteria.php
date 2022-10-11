<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Telemetry;

class TelemetryEventsQueryCriteria
{
    /**
     * @var int|null
     */
    protected ?int $maxAttemptsCount = null;

    /**
     * @var int|null
     */
    protected ?int $limit = null;

    /**
     * @var int|null
     */
    protected ?int $maxSyncTimestamp = null;

    /**
     * @return int|null
     */
    public function getMaxAttemptsCount(): ?int
    {
        return $this->maxAttemptsCount;
    }

    /**
     * @param int|null $maxAttemptsCount
     *
     * @return void
     */
    public function setMaxAttemptsCount(?int $maxAttemptsCount): void
    {
        $this->maxAttemptsCount = $maxAttemptsCount;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     *
     * @return void
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int|null
     */
    public function getMaxSyncTimestamp(): ?int
    {
        return $this->maxSyncTimestamp;
    }

    /**
     * @param int|null $maxSyncTimestamp
     *
     * @return void
     */
    public function setMaxSyncTimestamp(?int $maxSyncTimestamp): void
    {
        $this->maxSyncTimestamp = $maxSyncTimestamp;
    }
}
