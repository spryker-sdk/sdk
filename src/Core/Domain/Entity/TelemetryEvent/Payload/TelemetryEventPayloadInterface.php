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
    public static function getEventName(): string;

    /**
     * @return string
     */
    public static function getEventScope(): string;

    /**
     * @return int
     */
    public static function getEventVersion(): int;
}
