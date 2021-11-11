<?php

namespace Sdk\Transfer;

class TaskLogTransfer
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var string
     */
    protected string $event;

    /**
     * @var bool
     */
    protected bool $isSuccessful;

    /**
     * @var string|null
     */
    protected string $triggeredBy;

    /**
     * TaskLogTransfer constructor.
     * @param string $id
     * @param string $type
     * @param string $event
     * @param bool $isSuccessful
     * @param string $triggeredBy
     * @param string $context
     */
    public function __construct(string $id, string $type, string $event, bool $isSuccessful, string $triggeredBy, string $context)
    {
        $this->id = $id;
        $this->type = $type;
        $this->event = $event;
        $this->isSuccessful = $isSuccessful;
        $this->triggeredBy = $triggeredBy;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getTriggeredBy(): string
    {
        return $this->triggeredBy;
    }

    /**
     * @param string $triggeredBy
     *
     * @return static
     */
    public function setTriggeredBy(string $triggeredBy): self
    {
        $this->triggeredBy = $triggeredBy;

        return $this;
    }

    /**
     * @var string
     */
    protected string $context;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return static
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @param string $event
     *
     * @return static
     */
    public function setEvent(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * @param bool $isSuccessful
     *
     * @return static
     */
    public function setISuccessful(bool $isSuccessful): self
    {
        $this->isSuccessful = $isSuccessful;

        return $this;
    }

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param string $context
     *
     * @return static
     */
    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }
}
