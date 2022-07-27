<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Event;

use SprykerSdk\SdkContracts\Events\EventInterface;

class Event implements EventInterface
{
    protected string $id;

    protected string $type;

    protected string $event;

    protected bool $isSuccessful;

    protected string $triggeredBy;

    protected string $context;

    /**
     * @param string $id
     * @param string $type
     * @param string $event
     * @param bool $isSuccessful
     * @param string $triggeredBy
     * @param string $context
     */
    public function __construct(
        string $id,
        string $type,
        string $event,
        bool $isSuccessful,
        string $triggeredBy,
        string $context
    ) {
        $this->context = $context;
        $this->triggeredBy = $triggeredBy;
        $this->isSuccessful = $isSuccessful;
        $this->event = $event;
        $this->type = $type;
        $this->id = $id;
    }

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
