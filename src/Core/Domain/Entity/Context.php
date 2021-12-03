<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\Sdk\Contracts\Entity\ContextInterface;
use SprykerSdk\Sdk\Contracts\Entity\MessageInterface;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\StagedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface;

//@todo perfect use case for marks DTO library
class Context implements ContextInterface
{
    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected array $requiredPlaceholders = [];

    /**
     * @var array<string, mixed>
     */
    protected array $resolvedValues = [];

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\MessageInterface>
     */
    protected array $messages = [];

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    protected array $tasks = [];

    /**
     * @var array<string>
     */
    protected array $availableStages = self::DEFAULT_STAGES;

    /**
     * @var array<string>
     */
    protected array $requiredStages = self::DEFAULT_STAGES;

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface>
     */
    protected array $violationReports = [];

    protected int $exitCode = self::SUCCESS_EXIT_CODE;

    /**
     * @var array<string>
     */
    protected array $tags = [];

    /**
     * @var bool
     */
    protected bool $isDryRun = false;

    protected TaskInterface $task;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getRequiredPlaceholders(): array
    {
        return $this->requiredPlaceholders;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface $placeholder
     *
     * @return void
     */
    public function addRequiredPlaceholder(PlaceholderInterface $placeholder)
    {
        $this->requiredPlaceholders[] = $placeholder;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $requiredPlaceholders
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
    public function addResolvedValues(string $key, mixed $value)
    {
        $this->resolvedValues[$key] = $value;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\MessageInterface>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\MessageInterface $message
     *
     * @return void
     */
    public function addMessage(MessageInterface $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\MessageInterface> $messages
     *
     * @return void
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    public function getSubTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function addSubTask(TaskInterface $task)
    {
        $this->tasks[$task->getId()] = $task;
        $stage = $task instanceof StagedTaskInterface ? $task->getStage() : static::DEFAULT_STAGE;
        $this->availableStages[] = $stage;
    }

    /**
     * @return array<string>
     */
    public function getAvailableStages(): array
    {
        return array_unique($this->availableStages);
    }

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface>
     */
    public function getViolationReports(): array
    {
        return $this->violationReports;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function addViolationReport(ViolationReportInterface $violationReport)
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
     * @param array<string> $availableStages
     *
     * @return void
     */
    public function setAvailableStages(array $availableStages): void
    {
        $this->availableStages = $availableStages;
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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'tags' => $this->getTags(),
            'resolved_values' => $this->getResolvedValues(),
            'messages' => $this->getMessages(),
            'violation_reports' => ['list of violation report files'],
        ];
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
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function setTask(TaskInterface $task): void
    {
        $this->task = $task;
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface
     */
    public function getTask(): TaskInterface
    {
        return $this->task;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $subTasks
     *
     * @return void
     */
    public function setSubTasks(array $subTasks): void
    {
        array_map(function (TaskInterface $task) {
            $this->addSubTask($task);
        }, $subTasks);
    }
}
