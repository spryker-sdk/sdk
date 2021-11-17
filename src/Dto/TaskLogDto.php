<?php

namespace Sdk\Dto;

class TaskLogDto
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
     * @var string
     */
    protected string $sdkContext;

    /**
     * @var string|null
     */
    protected ?string $message = null;

    /**
     * TaskLogTransfer constructor.
     * @param string $id
     * @param string $type
     * @param string $event
     * @param bool $isSuccessful
     * @param string $triggeredBy
     * @param string $sdkContext
     * @param string|null $message
     */
    public function __construct(
        string $id,
        string $type,
        string $event,
        bool $isSuccessful,
        string $triggeredBy,
        string $sdkContext,
        string $message = null
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->event = $event;
        $this->isSuccessful = $isSuccessful;
        $this->triggeredBy = $triggeredBy;
        $this->sdkContext = $sdkContext;
        $this->message = $message;
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
    public function getSdkContext(): string
    {
        return $this->sdkContext;
    }

    /**
     * @param string $sdkContext
     *
     * @return static
     */
    public function setSdkContext(string $sdkContext): self
    {
        $this->sdkContext = $sdkContext;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     *
     * @return static
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
