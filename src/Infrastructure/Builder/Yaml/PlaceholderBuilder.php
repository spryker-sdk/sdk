<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskPoolInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class PlaceholderBuilder implements PlaceholderBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskPoolInterface
     */
    protected TaskPoolInterface $taskPool;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskPoolInterface $taskPool
     */
    public function __construct(TaskPoolInterface $taskPool)
    {
        $this->taskPool = $taskPool;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function buildPlaceholders(TaskYamlInterface $taskYaml): array
    {
        $data = $taskYaml->getTaskData();
        $placeholders = [];
        $taskPlaceholders = [];
        $taskPlaceholders[] = $data['placeholders'] ?? [];

        $taskPlaceholders = $this->extractPlaceholdersFromYamlTask($taskYaml, $taskPlaceholders);
        $taskPlaceholders = array_merge(...$taskPlaceholders);

        foreach ($taskPlaceholders as $placeholderData) {
            if ($placeholderData instanceof PlaceholderInterface) {
                $placeholders[$placeholderData->getName()] = $placeholderData;

                continue;
            }

            $placeholders[$placeholderData['name']] = $this->createPlaceholder($placeholderData);
        }

        return $placeholders;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     * @param array<string> $taskPlaceholders
     *
     * @return array
     */
    protected function extractPlaceholdersFromYamlTask(TaskYamlInterface $taskYaml, array $taskPlaceholders): array
    {
        $data = $taskYaml->getTaskData();

        if (!isset($data['type']) || $data['type'] !== TaskType::TASK_SET_TYPE) {
            return $taskPlaceholders;
        }

        $taskListData = $taskYaml->getTaskListData();

        foreach ($data['tasks'] as $task) {
            $taskPlaceholders[] = isset($taskListData[$task['id']]) ?
                $taskListData[$task['id']]['placeholders'] :
                $this->taskPool->getNotNestedTaskSet($task['id'])->getPlaceholders();
        }

        return $taskPlaceholders;
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
