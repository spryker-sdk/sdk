<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\ContextInterface as ContractContextInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Report\ReportInterface;

/**
 * Provides context information that shared between tasks.
 */
interface ContextInterface extends ContractContextInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder
     *
     * @return void
     */
    public function addRequiredPlaceholder(PlaceholderInterface $placeholder): void;

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface> $requiredPlaceholders
     *
     * @return void
     */
    public function setRequiredPlaceholders(array $requiredPlaceholders): void;

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
    public function addResolvedValues(string $key, $value): void;

    /**
     * @param array<string, \SprykerSdk\SdkContracts\Entity\MessageInterface> $messages
     *
     * @return void
     */
    public function setMessages(array $messages): void;

    /**
     * @return array<\SprykerSdk\SdkContracts\Report\ReportInterface>
     */
    public function getReports(): array;

    /**
     * @param \SprykerSdk\SdkContracts\Report\ReportInterface $report
     *
     * @return void
     */
    public function addReport(ReportInterface $report): void;

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
     * @param bool $isDryRun
     *
     * @return void
     */
    public function setIsDryRun(bool $isDryRun = true): void;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function setTask(TaskInterface $task): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @param array<string> $overwrites
     *
     * @return void
     */
    public function setOverwrites(array $overwrites): void;

    /**
     * @return array<string>
     */
    public function getOverwrites(): array;

    /**
     * @return array<string>
     */
    public function getInputStages(): array;

    /**
     * @param array<string> $inputStages
     *
     * @return void
     */
    public function setInputStages(array $inputStages): void;

    /**
     * @param array<string, int> $exitCodeMap
     *
     * @return void
     */
    public function setExitCodeMap(array $exitCodeMap): void;

    /**
     * @param string $format
     *
     * @return void
     */
    public function setFormat(string $format): void;

    /**
     * @return string
     */
    public function getFormat(): string;

    /**
     * @return array<string>
     */
    public function getRequiredStages(): array;

    /**
     * @return array<string>
     */
    public function getTags(): array;

    /**
     * @return bool
     */
    public function isDryRun(): bool;
}
