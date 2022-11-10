<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;

class Command implements CommandInterface, ErrorCommandInterface
{
    /**
     * @var string
     */
    protected string $command;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var bool
     */
    protected bool $hasStopOnError = false;

    /**
     * @var array<string>
     */
    protected array $tags;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    protected ?ConverterInterface $converter;

    /**
     * @var string
     */
    protected string $stage = ContextInterface::DEFAULT_STAGE;

    /**
     * @var string
     */
    protected string $errorMessage = '';

    /**
     * @todo :: POC. Interface instead.
     *
     * @var \SprykerSdk\Sdk\Core\Domain\Entity\CommandSplitter|null
     */
    protected ?CommandSplitter $commandSplitter = null;

    /**
     * @param string $command
     * @param string $type
     * @param bool $hasStopOnError
     * @param array<string> $tags
     * @param \SprykerSdk\SdkContracts\Entity\ConverterInterface|null $converter
     * @param string $stage
     * @param string $errorMessage
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\CommandSplitter|null $commandSplitter
     */
    public function __construct(
        string $command,
        string $type,
        bool $hasStopOnError = false,
        array $tags = [],
        ?ConverterInterface $converter = null,
        string $stage = ContextInterface::DEFAULT_STAGE,
        string $errorMessage = '',
        ?CommandSplitter $commandSplitter = null
    ) {
        $this->hasStopOnError = $hasStopOnError;
        $this->type = $type;
        $this->command = $command;
        $this->tags = $tags;
        $this->converter = $converter;
        $this->stage = $stage;
        $this->errorMessage = $errorMessage;
        $this->commandSplitter = $commandSplitter;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * {@inheritDoc}
     *
     * @return bool
     */
    public function hasStopOnError(): bool
    {
        return $this->hasStopOnError;
    }

    /**
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Entity\ConverterInterface|null
     */
    public function getConverter(): ?ConverterInterface
    {
        return $this->converter;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getStage(): string
    {
        return $this->stage;
    }

    /**
     * @param string $stage
     *
     * @return $this
     */
    public function setStage(string $stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\CommandSplitter|null
     */
    public function getCommandSplitter(): ?CommandSplitter
    {
        return $this->commandSplitter;
    }
}
