<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Dependency\Events;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use Symfony\Contracts\EventDispatcher\Event;

interface GuardEventInterface
{
    /**
     * @return \Symfony\Contracts\EventDispatcher\Event
     */
    public function getEvent(): Event;

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    public function getContext(): ?ContextInterface;

    /**
     * @return bool
     */
    public function isBlocked(): bool;

    /**
     * @param bool $blocked
     * @param string|null $reason
     *
     * @return mixed
     */
    public function setBlocked(bool $blocked, ?string $reason);

    /**
     * @return string|null
     */
    public function getBlockedReason(): ?string;
}
