<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Events;

use SprykerSdk\Sdk\Contracts\Events\EventInterface;

class Event implements EventInterface
{
    public function __construct(
        public string $id,
        public string $type,
        public string $event,
        public bool $isSuccessful,
        public string $triggeredBy,
        public string $context
    ){}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * @return string
     */
    public function getTriggeredBy(): string
    {
        return $this->triggeredBy;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }
}
