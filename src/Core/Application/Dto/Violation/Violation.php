<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Violation;

use SprykerSdk\SdkContracts\Report\Violation\ViolationFixInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;

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
     * @var \SprykerSdk\SdkContracts\Report\Violation\ViolationFixInterface
     */
    protected ?ViolationFixInterface $fix = null;

    /**
     * {@inheritDoc}
     *
     * @param string $id
     * @param string $message
     */
    public function __construct(string $id, string $message)
    {
        $this->id = $id;
        $this->message = $message;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isFixable(): bool
    {
        return $this->fixable;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function producedBy(): string
    {
        return $this->produced;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getSeverity(): string
    {
        return $this->severity;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function priority(): ?string
    {
        return $this->priority;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     *
     * @return int|null
     */
    public function getStartLine(): ?int
    {
        return $this->startLine;
    }

    /**
     * {@inheritDoc}
     *
     * @return int|null
     */
    public function getEndLine(): ?int
    {
        return $this->endLine;
    }

    /**
     * {@inheritDoc}
     *
     * @return int|null
     */
    public function getStartColumn(): ?int
    {
        return $this->startColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @return int|null
     */
    public function getEndColumn(): ?int
    {
        return $this->endColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getAdditionalAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationFixInterface|null
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
    public function setSeverity(string $severity)
    {
        $this->severity = $severity;

        return $this;
    }

    /**
     * @param bool $fixable
     *
     * @return $this
     */
    public function setFixable(bool $fixable)
    {
        $this->fixable = $fixable;

        return $this;
    }

    /**
     * @param string $produced
     *
     * @return $this
     */
    public function setProduced(string $produced)
    {
        $this->produced = $produced;

        return $this;
    }

    /**
     * @param string|null $priority
     *
     * @return $this
     */
    public function setPriority(?string $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @param string|null $class
     *
     * @return $this
     */
    public function setClass(?string $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @param int|null $startLine
     *
     * @return $this
     */
    public function setStartLine(?int $startLine)
    {
        $this->startLine = $startLine;

        return $this;
    }

    /**
     * @param int|null $endLine
     *
     * @return $this
     */
    public function setEndLine(?int $endLine)
    {
        $this->endLine = $endLine;

        return $this;
    }

    /**
     * @param int|null $startColumn
     *
     * @return $this
     */
    public function setStartColumn(?int $startColumn)
    {
        $this->startColumn = $startColumn;

        return $this;
    }

    /**
     * @param int|null $endColumn
     *
     * @return $this
     */
    public function setEndColumn(?int $endColumn)
    {
        $this->endColumn = $endColumn;

        return $this;
    }

    /**
     * @param string|null $method
     *
     * @return $this
     */
    public function setMethod(?string $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationFixInterface|null $fix
     *
     * @return $this
     */
    public function setFix(?ViolationFixInterface $fix)
    {
        $this->fix = $fix;

        return $this;
    }
}
