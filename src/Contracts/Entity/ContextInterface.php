<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

use SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface;

interface ContextInterface
{
    /**
     * @var string
     */
    public const DEFAULT_STAGE = 'default';

    /**
     * @var array<string>
     */
    public const DEFAULT_STAGES = [self::DEFAULT_STAGE];

    /**
     * @var int
     */
    public const SUCCESS_EXIT_CODE = 0;

    /**
     * @var int
     */
    public const FAILURE_EXIT_CODE = 1;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface>
     */
    public function getRequiredPlaceholders(): array;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface $placeholder
     *
     * @return void
     */
    public function addRequiredPlaceholder(PlaceholderInterface $placeholder);

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\PlaceholderInterface> $requiredPlaceholders
     *
     * @return void
     */
    public function setRequiredPlaceholders(array $requiredPlaceholders): void;

    /**
     * @return array<string, mixed>
     */
    public function getResolvedValues(): array;

    /**
     * @param array<string, mixed> $resolvedValues
     *
     * @return void
     */
    public function setResolvedValues(array $resolvedValues): void;

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addResolvedValues(string $key, mixed $value);

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\MessageInterface>
     */
    public function getMessages(): array;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\MessageInterface $message
     *
     * @return void
     */
    public function addMessage(MessageInterface $message);

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\MessageInterface> $messages
     *
     * @return void
     */
    public function setMessages(array $messages): void;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface>
     */
    public function getSubTasks(): array;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function addSubTask(TaskInterface $task);

    /**
     * @return array<string>
     */
    public function getAvailableStages(): array;

    /**
     * @return array<\SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface>
     */
    public function getViolationReports(): array;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Report\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function addViolationReport(ViolationReportInterface $violationReport);

    /**
     * @return int
     */
    public function getExitCode(): int;

    /**
     * @param int $exitCode
     *
     * @return void
     */
    public function setExitCode(int $exitCode): void;

    /**
     * @param array<string> $availableStages
     *
     * @return void
     */
    public function setAvailableStages(array $availableStages): void;

    /**
     * @return array<string>
     */
    public function getRequiredStages(): array;

    /**
     * @param array<string> $requiredStages
     *
     * @return void
     */
    public function setRequiredStages(array $requiredStages): void;

    /**
     * @param array<string> $tags
     *
     * @return void
     */
    public function setTags(array $tags): void;

    /**
     * @return array<string>
     */
    public function getTags(): array;

    /**
     * @return bool
     */
    public function isDryRun(): bool;

    /**
     * @param bool $isDryRun
     *
     * @return void
     */
    public function setIsDryRun(bool $isDryRun = true): void;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function setTask(TaskInterface $task): void;

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface
     */
    public function getTask(): TaskInterface;

    /**
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\TaskInterface> $subTasks
     *
     * @return void
     */
    public function setSubTasks(array $subTasks): void;
}
