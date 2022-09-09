<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Dto;

class TaskSetOverrideMapDto
{
    /**
     * @var array<string, bool>
     */
    protected array $stopOnErrorMap;

    /**
     * @var array<string, array<string>>
     */
    protected array $tagsMap;

    /**
     * @var array<string, array<string, string>>
     */
    protected array $sharedPlaceholdersMap;

    /**
     * @var array<string, array<string, array>>
     */
    protected array $overridePlaceholdersMap;

    /**
     * @param array<string, bool> $stopOnErrorMap
     * @param array<string, array<string>> $tagsMap
     * @param array<string, array<string, string>> $sharedPlaceholdersMap
     * @param array<string, array<string, array>> $overridePlaceholdersMap
     */
    public function __construct(
        array $stopOnErrorMap = [],
        array $tagsMap = [],
        array $sharedPlaceholdersMap = [],
        array $overridePlaceholdersMap = []
    ) {
        $this->stopOnErrorMap = $stopOnErrorMap;
        $this->tagsMap = $tagsMap;
        $this->sharedPlaceholdersMap = $sharedPlaceholdersMap;
        $this->overridePlaceholdersMap = $overridePlaceholdersMap;
    }

    /**
     * @return array
     */
    public function getStopOnErrorMap(): array
    {
        return $this->stopOnErrorMap;
    }

    /**
     * @param string $taskId
     *
     * @return bool|null
     */
    public function getTaskStopOnError(string $taskId): ?bool
    {
        return $this->stopOnErrorMap[$taskId] ?? null;
    }

    /**
     * @return array<string, array<string>>
     */
    public function getTagsMap(): array
    {
        return $this->tagsMap;
    }

    /**
     * @param string $taskId
     *
     * @return array<string>|null
     */
    public function getTaskTags(string $taskId): ?array
    {
        return $this->tagsMap[$taskId] ?? null;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getSharedPlaceholdersMap(): array
    {
        return $this->sharedPlaceholdersMap;
    }

    /**
     * @return array<string, array<string, array>>
     */
    public function getOverridePlaceholdersMap(): array
    {
        return $this->overridePlaceholdersMap;
    }

    /**
     * @param string $taskId
     *
     * @return array<string, mixed>|null
     */
    public function getTaskOverridePlaceholders(string $taskId): ?array
    {
        return $this->overridePlaceholdersMap[$taskId] ?? null;
    }
}
