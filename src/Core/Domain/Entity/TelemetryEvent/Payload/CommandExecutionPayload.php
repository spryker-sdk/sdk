<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload;

class CommandExecutionPayload implements TelemetryEventPayloadInterface
{
    /**
     * @var string
     */
    public const DEFAULT_SCOPE = 'SDK';

    /**
     * @var string
     */
    public const EVENT_NAME = 'command_execution';

    /**
     * @var int
     */
    public const EVENT_VERSION = 1;

    /**
     * @var int
     */
    protected const COMMAND_SUCCESS_EXECUTION = 0;

    /**
     * @var string
     */
    protected string $commandName;

    /**
     * @var array
     */
    protected array $inputArguments;

    /**
     * @var array
     */
    protected array $inputOptions;

    /**
     * @var string
     */
    protected string $errorMessage;

    /**
     * @var int
     */
    protected int $exitCode;

    /**
     * @param string $commandName
     * @param array $inputArguments
     * @param array $inputOptions
     * @param string $errorMessage
     * @param int $exitCode
     */
    public function __construct(
        string $commandName,
        array $inputArguments,
        array $inputOptions,
        string $errorMessage = '',
        int $exitCode = self::COMMAND_SUCCESS_EXECUTION
    ) {
        $this->commandName = $commandName;
        $this->inputArguments = $inputArguments;
        $this->inputOptions = $inputOptions;
        $this->errorMessage = $errorMessage;
        $this->exitCode = $exitCode;
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * @return array
     */
    public function getInputArguments(): array
    {
        return $this->inputArguments;
    }

    /**
     * @return array
     */
    public function getInputOptions(): array
    {
        return $this->inputOptions;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return int
     */
    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return static::EVENT_NAME;
    }

    /**
     * @return string
     */
    public function getEventScope(): string
    {
        return static::DEFAULT_SCOPE;
    }

    /**
     * @return int
     */
    public function getEventVersion(): int
    {
        return static::EVENT_VERSION;
    }
}
