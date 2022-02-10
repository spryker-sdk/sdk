<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class Context implements ContextInterface
{
    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected array $requiredPlaceholders = [];

    /**
     * @var array<string, mixed>
     */
    protected array $resolvedValues = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\MessageInterface>
     */
    protected array $messages = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $tasks = [];

    /**
     * @var array<string>
     */
    protected array $requiredStages = self::DEFAULT_STAGES;

    /**
     * @var array<\SprykerSdk\SdkContracts\Violation\ViolationReportInterface>
     */
    protected array $violationReports = [];

    /**
     * @var int
     */
    protected int $exitCode = self::SUCCESS_EXIT_CODE;

    /**
     * @var array<string>
     */
    protected array $tags = [];

    /**
     * @var bool
     */
    protected bool $isDryRun = false;

    /**
     * @var \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected TaskInterface $task;

    /**
     * @var string
     */
    protected string $name = 'sdk';

    /**
     * @var array<string>
     */
    protected array $inputStages = [];

    /**
     * @var array<string>
     */
    protected array $overwrites = [];

    /**
     * @var array<string, int>
     */
    protected array $exitCodeMap = [];

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function getRequiredPlaceholders(): array
    {
        return $this->requiredPlaceholders;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return void
     */
    public function addRequiredPlaceholder(PlaceholderInterface $placeholder): void
    {
        $this->requiredPlaceholders[] = $placeholder;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $requiredPlaceholders
     *
     * @return void
     */
    public function setRequiredPlaceholders(array $requiredPlaceholders): void
    {
        $this->requiredPlaceholders = [];

        array_map(function (PlaceholderInterface $placeholder): void {
            $this->addRequiredPlaceholder($placeholder);
        }, $requiredPlaceholders);
    }

    /**
     * @return array<string, mixed>
     */
    public function getResolvedValues(): array
    {
        return $this->resolvedValues;
    }

    /**
     * @param array<string, mixed> $resolvedValues
     *
     * @return void
     */
    public function setResolvedValues(array $resolvedValues): void
    {
        $this->resolvedValues = $resolvedValues;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addResolvedValues(string $key, mixed $value): void
    {
        $this->resolvedValues[$key] = $value;
    }

    /**
     * @return array<string, \SprykerSdk\SdkContracts\Entity\MessageInterface>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param string $id
     * @param \SprykerSdk\SdkContracts\Entity\MessageInterface $message
     *
     * @return void
     */
    public function addMessage(string $id, MessageInterface $message): void
    {
        $this->messages[$id] = $message;
    }

    /**
     * @param array<string, \SprykerSdk\SdkContracts\Entity\MessageInterface> $messages
     *
     * @return void
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Violation\ViolationReportInterface>
     */
    public function getViolationReports(): array
    {
        return $this->violationReports;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function addViolationReport(ViolationReportInterface $violationReport): void
    {
        $this->violationReports[] = $violationReport;
    }

    /**
     * @return int
     */
    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * @param int $exitCode
     *
     * @return void
     */
    public function setExitCode(int $exitCode): void
    {
        $this->exitCode = $exitCode;
    }

    /**
     * @return array<string>
     */
    public function getRequiredStages(): array
    {
        return $this->requiredStages;
    }

    /**
     * @param array<string> $requiredStages
     *
     * @return void
     */
    public function setRequiredStages(array $requiredStages): void
    {
        $this->requiredStages = $requiredStages;
    }

    /**
     * @param array<string> $tags
     *
     * @return void
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return bool
     */
    public function isDryRun(): bool
    {
        return $this->isDryRun;
    }

    /**
     * @param bool $isDryRun
     *
     * @return void
     */
    public function setIsDryRun(bool $isDryRun = true): void
    {
        $this->isDryRun = $isDryRun;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function setTask(TaskInterface $task): void
    {
        $this->task = $task;
        $this->name = $task->getId();
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array<string>
     */
    public function getOverwrites(): array
    {
        return $this->overwrites;
    }

    /**
     * @param array<string> $overwrites
     *
     * @return void
     */
    public function setOverwrites(array $overwrites): void
    {
        $this->overwrites = $overwrites;
    }

    /**
     * @return array
     */
    public function getInputStages(): array
    {
        return $this->inputStages;
    }

    /**
     * @param array $inputStages
     *
     * @return void
     */
    public function setInputStages(array $inputStages): void
    {
        $this->inputStages = $inputStages;
    }

    /**
     * @return array<string, int>
     */
    public function getExitCodeMap(): array
    {
        return $this->exitCodeMap;
    }

    /**
     * @param array<string, int> $exitCodeMap
     *
     * @return void
     */
    public function setExitCodeMap(array $exitCodeMap): void
    {
        $this->exitCodeMap = $exitCodeMap;
    }

    /**
     * @param string $id
     * @param int $code
     *
     * @return void
     */
    public function addExitCode(string $id, int $code): void
    {
        $this->exitCodeMap[$id] = $code;
    }
}
