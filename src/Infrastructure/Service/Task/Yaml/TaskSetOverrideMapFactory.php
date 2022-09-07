<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml;

use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMapBuilder;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMapBuilderFactory;

class TaskSetOverrideMapFactory
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMapBuilderFactory
     */
    protected TaskSetOverrideMapBuilderFactory $taskSetOverrideMapBuilderFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMapBuilderFactory $taskSetOverrideMapBuilderFactory
     */
    public function __construct(TaskSetOverrideMapBuilderFactory $taskSetOverrideMapBuilderFactory)
    {
        $this->taskSetOverrideMapBuilderFactory = $taskSetOverrideMapBuilderFactory;
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap
     */
    public function createTaskSetOverrideMap(array $taskSetConfiguration): TaskSetOverrideMap
    {
        $taskSetOverrideMapBuilder = $this->taskSetOverrideMapBuilderFactory->getBuilder();

        $this->populateSharadPlaceholders($taskSetOverrideMapBuilder, $taskSetConfiguration);

        foreach ($taskSetConfiguration['tasks'] as $subTask) {
            $this->populateSubTasks($taskSetOverrideMapBuilder, $subTask);
        }

        return $taskSetOverrideMapBuilder->getTaskSetOverrideMap();
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMapBuilder $taskSetOverrideMapBuilder
     * @param array<string, mixed> $taskSetConfiguration
     *
     * @return void
     */
    protected function populateSharadPlaceholders(TaskSetOverrideMapBuilder $taskSetOverrideMapBuilder, array $taskSetConfiguration): void
    {
        if (!isset($taskSetConfiguration['shared_placeholders'])) {
            return;
        }

        foreach ($taskSetConfiguration['shared_placeholders'] as $placeholderName => $config) {
            $taskSetOverrideMapBuilder->addSharedPlaceholder($placeholderName, $config ?? []);
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMapBuilder $taskSetOverrideMapBuilder
     * @param array<string, mixed> $subTask
     *
     * @return void
     */
    protected function populateSubTasks(TaskSetOverrideMapBuilder $taskSetOverrideMapBuilder, array $subTask): void
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
