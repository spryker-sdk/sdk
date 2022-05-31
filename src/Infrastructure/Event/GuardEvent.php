<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Event;

use SprykerSdk\Sdk\Extension\Dependency\Events\GuardEventInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use Symfony\Component\Workflow\Event\Event;

class GuardEvent implements GuardEventInterface
{
    /**
     * @var \Symfony\Component\Workflow\Event\Event
     */
    protected Event $event;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    protected ?ContextInterface $context;

    /**
     * @var array
     */
    protected array $blockers = [];

    /**
     * @param \Symfony\Component\Workflow\Event\Event $event
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface|null $context
     */
    public function __construct(Event $event, ?ContextInterface $context = null)
    {
        $this->event = $event;
        $this->context = $context;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface|null
     */
    public function getContext(): ?ContextInterface
    {
        return $this->context;
    }

    /**
     * @return \Symfony\Component\Workflow\Event\Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return count($this->blockers) !== 0;
    }

    /**
     * @param bool $blocked
     * @param string|null $reason
     *
     * @return void
     */
    public function setBlocked(bool $blocked, ?string $reason): void
    {
        if (!$blocked) {
            $this->blockers = [];

            return;
        }

        $this->blockers[] = $reason;
    }

    /**
     * @return string|null
     */
    public function getBlockedReason(): ?string
    {
        if (!$this->isBlocked()) {
            return null;
        }

        return implode(PHP_EOL, array_filter($this->blockers));
    }
}
