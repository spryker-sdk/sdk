<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Report\ReportInterface;

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
    protected array $requiredStages = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Report\ReportInterface>
     */
    protected array $reports = [];

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
     * @var string
     */
    protected string $format;

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
    public function addResolvedValues(string $key, $value): void
    {
        $this->resolvedValues[$key] = $value;
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, \SprykerSdk\SdkContracts\Entity\MessageInterface>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * {@inheritDoc}
     *
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
     * @return array<\SprykerSdk\SdkContracts\Report\ReportInterface>
     */
    public function getReports(): array
    {
        return $this->reports;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\ReportInterface $report
     *
     * @return void
     */
    public function addReport(ReportInterface $report): void
    {
        $this->reports[] = $report;
    }

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @param string $id
     * @param int $code
     *
     * @return void
     */
    public function addExitCode(string $id, int $code): void
    {
        $this->exitCodeMap[$id] = $code;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return void
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }
}
