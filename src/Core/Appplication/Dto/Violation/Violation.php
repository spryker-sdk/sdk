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
    protected string $severity;

    /**
     * @var bool
     */
    protected bool $fixable;

    /**
     * @var string
     */
    protected string $produced;

    /**
     * @var string|null
     */
    protected ?string $priority;

    /**
     * @var string|null
     */
    protected ?string $class;

    /**
     * @var int|null
     */
    protected ?int $startLine;

    /**
     * @var int|null
     */
    protected ?int $endLine;

    /**
     * @var int|null
     */
    protected ?int $startColumn;

    /**
     * @var int|null
     */
    protected ?int $endColumn;

    /**
     * @var string|null
     */
    protected ?string $method;

    /**
     * @var array
     */
    protected array $attributes;

    /**
     * @var \SprykerSdk\SdkContracts\Violation\ViolationFixInterface
     */
    protected ?ViolationFixInterface $fix;

    /**
     * @param string $id
     * @param string $message
     * @param string $severity
     * @param string|null $priority
     * @param string|null $class
     * @param int|null $startLine
     * @param int|null $endLine
     * @param int|null $startColumn
     * @param int|null $endColumn
     * @param string|null $method
     * @param array $attributes
     * @param bool $fixable
     * @param string $produced
     * @param \SprykerSdk\SdkContracts\Violation\ViolationFixInterface|null $fix
     */
    public function __construct(
        string $id,
        string $message,
        string $severity = ViolationInterface::SEVERITY_ERROR,
        ?string $priority = null,
        ?string $class = null,
        ?int $startLine = null,
        ?int $endLine = null,
        ?int $startColumn = null,
        ?int $endColumn = null,
        ?string $method = null,
        array $attributes = [],
        bool $fixable = false,
        string $produced = '',
        ?ViolationFixInterface $fix = null
    ) {
        $this->id = $id;
        $this->message = $message;
        $this->severity = $severity;
        $this->priority = $priority;
        $this->class = $class;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
        $this->startColumn = $startColumn;
        $this->endColumn = $endColumn;
        $this->method = $method;
        $this->attributes = $attributes;
        $this->fixable = $fixable;
        $this->produced = $produced;
        $this->fix = $fix;
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
}
