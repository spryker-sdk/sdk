<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\Yaml;

use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoBuilderFactory;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto;

class TaskSetOverrideMapFactory
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoBuilderFactory
     */
    protected TaskSetOverrideMapDtoBuilderFactory $taskSetOverrideMapBuilderFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoBuilderFactory $taskSetOverrideMapBuilderFactory
     */
    public function __construct(TaskSetOverrideMapDtoBuilderFactory $taskSetOverrideMapBuilderFactory)
    {
        $this->taskSetOverrideMapBuilderFactory = $taskSetOverrideMapBuilderFactory;
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto
     */
    public function createTaskSetOverrideMap(array $taskSetConfiguration): TaskSetOverrideMapDto
    {
        $taskSetOverrideMapBuilder = $this->taskSetOverrideMapBuilderFactory->getBuilder();

        $this->populateSharadPlaceholders($taskSetOverrideMapBuilder, $taskSetConfiguration);

        foreach ($taskSetConfiguration['tasks'] as $subTask) {
            $this->populateSubTasks($taskSetOverrideMapBuilder, $subTask);
        }

        return $taskSetOverrideMapBuilder->getTaskSetOverrideMap();
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoBuilder $taskSetOverrideMapBuilder
     * @param array<string, mixed> $taskSetConfiguration
     *
     * @return void
     */
    protected function populateSharadPlaceholders(
        TaskSetOverrideMapDtoBuilder $taskSetOverrideMapBuilder,
        array $taskSetConfiguration
    ): void {
        if (!isset($taskSetConfiguration['shared_placeholders'])) {
            return;
        }

        foreach ($taskSetConfiguration['shared_placeholders'] as $placeholderName => $config) {
            $taskSetOverrideMapBuilder->addSharedPlaceholder($placeholderName, $config ?? []);
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetOverrideMap\TaskSetOverrideMapDtoBuilder $taskSetOverrideMapBuilder
     * @param array<string, mixed> $subTask
     *
     * @return void
     */
    protected function populateSubTasks(TaskSetOverrideMapDtoBuilder $taskSetOverrideMapBuilder, array $subTask): void
    {
        if (isset($subTask['stop_on_error'])) {
            $taskSetOverrideMapBuilder->addStopOnError($subTask['id'], (bool)$subTask['stop_on_error']);
        }

        if (isset($subTask['tags'])) {
            $taskSetOverrideMapBuilder->addTags($subTask['id'], $subTask['tags']);
        }

        if (isset($subTask['placeholder_overrides'])) {
            foreach ($subTask['placeholder_overrides'] as $placeholderName => $placeholderDefinition) {
                $taskSetOverrideMapBuilder->addOverridePlaceholderDefinition($subTask['id'], $placeholderName, $placeholderDefinition);
            }
        }
    }
}
