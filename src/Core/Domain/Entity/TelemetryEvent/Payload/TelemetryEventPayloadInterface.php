<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload;

interface TelemetryEventPayloadInterface
{
    /**
     * @return string
     */
    public function getEventName(): string;

    /**
     * @return string
     */
    public function getEventScope(): string;

    /**
     * @return int
     */
    public function getEventVersion(): int;
}
