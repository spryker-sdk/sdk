<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Events;

class Event
{
    public function __construct(
        public string $id,
        public string $type,
        public string $event,
        public bool $isSuccessful,
        public string $triggeredBy,
        public string $context
    ){}
}