<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder;

use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Enum\Task;

class PlaceholderTaskPartBuilder implements TaskPartBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage
     */
    protected TaskStorage $storage;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage $storage
     */
    public function __construct(TaskStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto
     */
    public function addPart(TaskYamlCriteriaDto $criteriaDto, TaskYamlResultDto $resultDto): TaskYamlResultDto
    {
        $taskPlaceholders = $criteriaDto->getTaskData()['placeholders'] ?? [];
        $taskPlaceholders = $this->addTaskSetPlaceholders($criteriaDto, $taskPlaceholders);

        foreach ($taskPlaceholders as $taskPlaceholder) {
            if ($taskPlaceholder instanceof PlaceholderInterface) {
                $resultDto->addPlaceholder($taskPlaceholder);

                continue;
            }

            if (is_array($taskPlaceholder)) {
                $placeholder = $this->createPlaceholder($taskPlaceholder);
                $resultDto->addPlaceholder($placeholder);
            }
        }

        return $resultDto;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     * @param array $taskPlaceholders
     *
     * @return array
     */
    protected function addTaskSetPlaceholders(TaskYamlCriteriaDto $criteriaDto, array $taskPlaceholders): array
    {
        $taskData = $criteriaDto->getTaskData();

        if (isset($taskData['type']) && $taskData['type'] !== Task::TYPE_TASK_SET) {
            return $taskPlaceholders;
        }

        if (!array_key_exists('tasks', $taskData)) {
            return $taskPlaceholders;
        }

        $taskListData = $criteriaDto->getTaskListData();
        foreach ($taskData['tasks'] as $task) {
            if (
                isset($taskListData[$task['id']]['placeholders'])
                && is_array($taskListData[$task['id']]['placeholders'])
            ) {
                $taskPlaceholders[] = $taskListData[$task['id']]['placeholders'];

                continue;
            }

            $existingTask = $this->storage->getTaskById($task['id']);
            if ($existingTask instanceof TaskInterface) {
                $taskPlaceholders[] = $existingTask->getPlaceholders();
            }
        }

        return array_merge(...$taskPlaceholders);
    }

    /**
     * @param array $placeholderData
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholder(array $placeholderData): PlaceholderInterface
    {
        return new Placeholder(
            $placeholderData['name'],
            $placeholderData['value_resolver'],
            $placeholderData['configuration'] ?? [],
            $placeholderData['optional'] ?? false,
        );
    }
}
