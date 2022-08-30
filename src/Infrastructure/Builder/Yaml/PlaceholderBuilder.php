<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Service\TaskPool;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class PlaceholderBuilder implements PlaceholderBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\TaskPool
     */
    protected TaskPool $taskPool;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskPool $taskPool
     */
    public function __construct(TaskPool $taskPool)
    {
        $this->taskPool = $taskPool;
    }

    /**
     * @param array $data
     * @param array $taskListData
     * @param array $tags
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function buildPlaceholders(array $data, array $taskListData, array $tags = []): array
    {
        $placeholders = [];
        $taskPlaceholders = [];
        $taskPlaceholders[] = $data['placeholders'] ?? [];

        if (isset($data['type']) && $data['type'] === TaskType::TASK_SET_TYPE) {
            foreach ($data['tasks'] as $task) {
                $taskTags = $task['tags'] ?? [];
                if ($tags && !array_intersect($tags, $taskTags)) {
                    continue;
                }
                $taskPlaceholders[] = isset($taskListData[$task['id']]) ?
                    $taskListData[$task['id']]['placeholders'] :
                    $this->taskPool->getTasks()[$task['id']]->getPlaceholders();
            }
        }
        $taskPlaceholders = array_merge(...$taskPlaceholders);

        foreach ($taskPlaceholders as $placeholderData) {
            if ($placeholderData instanceof PlaceholderInterface) {
                $placeholders[$placeholderData->getName()] = $placeholderData;

                continue;
            }

            $placeholderName = $placeholderData['name'];
            $placeholders[$placeholderName] = new Placeholder(
                $placeholderName,
                $placeholderData['value_resolver'],
                $placeholderData['configuration'] ?? [],
                $placeholderData['optional'] ?? false,
            );
        }

        return $placeholders;
    }
}
