<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use JsonSerializable;
use SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface;
use SprykerSdk\Sdk\Contracts\Entity\StagedTaskInterface;
use SprykerSdk\Sdk\Contracts\Entity\TaskInterface;
use SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface;

//@todo perfect use case for marsk DTO library
class Context implements JsonSerializable
{
    /**
     * @var int
     */
    public const SUCCESS_STATUS_CODE = 0;

    /**
     * @var string
     */
    public const DEFAULT_STAGE = 'default';

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    protected array $requiredPlaceholders = [];

    /**
     * @var array<string, mixed>
     */
    protected array $resolvedValues = [];

    /**
     * @var array<\SprykerSdk\Sdk\Core\Domain\Entity\Message>
     */
    protected array $messages = [];

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    protected array $tasks = [];

    /**
     * @var array<string>
     */
    protected array $availableStages = [self::DEFAULT_STAGE];

    /**
     * @var array<string>
     */
    protected array $requiredStages = [self::DEFAULT_STAGE];

    /**
     * @var array<\SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface>
     */
    protected array $violationReports = [];

    protected int $result = self::SUCCESS_STATUS_CODE;

    /**
     * @var array<string>
     */
    protected array $tags = [];

    /**
     * @var bool
     */
    protected bool $isDryRun = false;

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
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Message>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Message $message
     *
     * @return void
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Message> $messages
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
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function addTask(TaskInterface $task)
    {
        $this->tasks[$task->getId()] = $task;
        $stage = $task instanceof StagedTaskInterface ? $task->getStage() : static::DEFAULT_STAGE;
        $this->availableStages[] = $stage;
    }

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $tasks
     *
     * @return void
     */
    public function setTasks(array $tasks): void
    {
        array_map(function (TaskInterface $task) {
            $this->addTask($task);
        }, $tasks);
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
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * @param int $result
     *
     * @return void
     */
    public function setResult(int $result): void
    {
        $this->result = $result;
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
     * @param array $data
     *
     * @return void
     */
    public function fromArray(array $data)
    {
        if (array_key_exists('tags', $data) && is_array($data['tags'])) {
            $this->tags = $data['tags'];
        }

        if (array_key_exists('resolved_values', $data) && is_array($data['resolved_values'])) {
            $this->resolvedValues = $data['resolved_values'];
        }

        if (array_key_exists('messages', $data) && is_array($data['messages'])) {
            $this->messages = array_map(function (array $messageData): Message {
                return new Message($messageData['message'], $messageData['verbosity'] ?? Message::INFO);
            }, $data['messages']);
        }
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
}
