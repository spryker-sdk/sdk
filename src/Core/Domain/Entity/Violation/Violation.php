<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\Violation;

use SprykerSdk\Sdk\Contracts\Violation\ViolationInterface;

class Violation implements ViolationInterface
{
    protected string $id;

    protected string $message;

    protected bool $fixable;

    protected string $produced;

    protected ?string $severity;

    protected ?string $class;

    protected ?int $startLine;

    protected ?int $endLine;

    protected ?int $startColumn;

    protected ?int $endColumn;

    protected ?string $method;

    protected array $attributes;

    /**
     * @param string $id
     * @param string $message
     * @param string|null $severity
     * @param string|null $class
     * @param int|null $startLine
     * @param int|null $endLine
     * @param int|null $startColumn
     * @param int|null $endColumn
     * @param string|null $method
     * @param array $attributes
     * @param bool $fixable
     * @param string $produced
     */
    public function __construct(
        string $id,
        string $message,
        ?string $severity,
        ?string $class,
        ?int $startLine,
        ?int $endLine,
        ?int $startColumn,
        ?int $endColumn,
        ?string $method,
        array $attributes = [],
        bool $fixable = false,
        string $produced = ''
    ) {
        $this->id = $id;
        $this->message = $message;
        $this->severity = $severity;
        $this->class = $class;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
        $this->startColumn = $startColumn;
        $this->endColumn = $endColumn;
        $this->method = $method;
        $this->attributes = $attributes;
        $this->fixable = $fixable;
        $this->produced = $produced;
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
     * @return string|null
     */
    public function severity(): ?string
    {
        return $this->severity;
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
}
