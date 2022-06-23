<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity\TelemetryEvent\Payload;

use SprykerSdk\SdkContracts\Entity\Telemetry\TelemetryEventPayloadInterface;

class CommandSuccessfulExecutionPayload implements TelemetryEventPayloadInterface
{
    /**
     * @var string
     */
    protected const EVENT_NAME = 'command_success_execution';

    /**
     * @var int
     */
    protected const EVENT_VERSION = 1;

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
     * @param string $commandName
     * @param array $inputArguments
     * @param array $inputOptions
     */
    public function __construct(string $commandName, array $inputArguments, array $inputOptions)
    {
        $this->commandName = $commandName;
        $this->inputArguments = $inputArguments;
        $this->inputOptions = $inputOptions;
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
    public static function getEventName(): string
    {
        return static::EVENT_NAME;
    }

    /**
     * @return int
     */
    public static function getLatestVersion(): int
    {
        return static::EVENT_VERSION;
    }
}
