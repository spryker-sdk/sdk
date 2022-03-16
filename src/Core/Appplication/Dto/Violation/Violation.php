<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dto\Violation;

use SprykerSdk\SdkContracts\Violation\ViolationFixInterface;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;

class Violation implements ViolationInterface
{
    /**
     * @var string
     */
    protected string $id;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var string
     */
    protected string $severity = ViolationInterface::SEVERITY_ERROR;

    /**
     * @var bool
     */
    protected bool $fixable = false;

    /**
     * @var string
     */
    protected string $produced = '';

    /**
     * @var string|null
     */
    protected ?string $priority = null;

    /**
     * @var string|null
     */
    protected ?string $class = null;

    /**
     * @var int|null
     */
    protected ?int $startLine = null;

    /**
     * @var int|null
     */
    protected ?int $endLine = null;

    /**
     * @var int|null
     */
    protected ?int $startColumn = null;

    /**
     * @var int|null
     */
    protected ?int $endColumn = null;

    /**
     * @var string|null
     */
    protected ?string $method = null;

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @var \SprykerSdk\SdkContracts\Violation\ViolationFixInterface
     */
    protected ?ViolationFixInterface $fix = null;

    /**
     * @param string $id
     * @param string $message
     */
    public function __construct(string $id, string $message)
    {
        $this->id = $id;
        $this->message = $message;
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function isFixable(): bool
    {
        return $this->fixable;
    }

    /**
     * @return string
     */
    public function producedBy(): string
    {
        return $this->produced;
    }

    /**
     * INFO, WARNING, ERROR
     *
     * @return string
     */
    public function getSeverity(): string
    {
        return $this->severity;
    }

    /**
     * @return string|null
     */
    public function priority(): ?string
    {
        return $this->priority;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @return int|null
     */
    public function getStartLine(): ?int
    {
        return $this->startLine;
    }

    /**
     * @return int|null
     */
    public function getEndLine(): ?int
    {
        return $this->endLine;
    }

    /**
     * @return int|null
     */
    public function getStartColumn(): ?int
    {
        return $this->startColumn;
    }

    /**
     * @return int|null
     */
    public function getEndColumn(): ?int
    {
        return $this->endColumn;
    }

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Violation\ViolationFixInterface|null
     */
    public function getFix(): ?ViolationFixInterface
    {
        return $this->fix;
    }

    /**
     * @param string $severity
     *
     * @return $this
     */
    public function setSeverity(string $severity): self
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * @param bool $fixable
     *
     * @return $this
     */
    public function setFixable(bool $fixable): self
    {
        $this->fixable = $fixable;

        return $this;
    }

    /**
     * @param string $produced
     *
     * @return $this
     */
    public function setProduced(string $produced): self
    {
        $this->produced = $produced;

        return $this;
    }

    /**
     * @param string|null $priority
     *
     * @return $this
     */
    public function setPriority(?string $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param string|null $class
     *
     * @return $this
     */
    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @param int|null $startLine
     *
     * @return $this
     */
    public function setStartLine(?int $startLine): self
    {
        $this->startLine = $startLine;

        return $this;
    }

    /**
     * @param int|null $endLine
     *
     * @return $this
     */
    public function setEndLine(?int $endLine): self
    {
        $this->endLine = $endLine;

        return $this;
    }

    /**
     * @param int|null $startColumn
     *
     * @return $this
     */
    public function setStartColumn(?int $startColumn): self
    {
        $this->startColumn = $startColumn;

        return $this;
    }

    /**
     * @param int|null $endColumn
     *
     * @return $this
     */
    public function setEndColumn(?int $endColumn): self
    {
        $this->endColumn = $endColumn;

        return $this;
    }

    /**
     * @param string|null $method
     *
     * @return $this
     */
    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationFixInterface|null $fix
     *
     * @return $this
     */
    public function setFix(?ViolationFixInterface $fix): self
    {
        $this->fix = $fix;

        return $this;
    }
}
