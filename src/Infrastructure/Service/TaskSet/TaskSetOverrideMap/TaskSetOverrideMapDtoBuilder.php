<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskSetOverrideMap;

use SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto;

class TaskSetOverrideMapDtoBuilder
{
    /**
     * @var array<string, bool>
     */
    protected array $stopOnErrorMap = [];

    /**
     * @var array<string, array<string>>
     */
    protected array $tagsMap = [];

    /**
     * @var array<string, array<string, string>>
     */
    protected array $sharedPlaceholderMap = [];

    /**
     * @var array<string, array<string, array>>
     */
    protected array $overridePlaceholderMap = [];

    /**
     * @param string $taskId
     * @param bool $stopOnError
     *
     * @return void
     */
    public function addStopOnError(string $taskId, bool $stopOnError): void
    {
        $this->stopOnErrorMap[$taskId] = $stopOnError;
    }

    /**
     * @param string $taskId
     * @param array<string> $tags
     *
     * @return void
     */
    public function addTags(string $taskId, array $tags): void
    {
        $this->tagsMap[$taskId] = $tags;
    }

    /**
     * @param string $placeholderName
     * @param array<string, mixed> $config
     *
     * @return void
     */
    public function addSharedPlaceholder(string $placeholderName, array $config = []): void
    {
        $this->sharedPlaceholderMap[$placeholderName] = $config;
    }

    /**
     * @param string $taskId
     * @param string $placeholderName
     * @param array<string, mixed> $overridePlaceholderDefinition
     *
     * @return void
     */
    public function addOverridePlaceholderDefinition(
        string $taskId,
        string $placeholderName,
        array $overridePlaceholderDefinition
    ): void {
        $this->overridePlaceholderMap[$taskId][$placeholderName] = $overridePlaceholderDefinition;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto
     */
    public function getTaskSetOverrideMap(): TaskSetOverrideMapDto
    {
        return new TaskSetOverrideMapDto(
            $this->stopOnErrorMap,
            $this->tagsMap,
            $this->sharedPlaceholderMap,
            $this->overridePlaceholderMap,
        );
    }
}
