<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Reader;

use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class TaskYamlPlaceholderReader
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage
     */
    protected TaskStorage $taskStorage;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage $taskStorage
     */
    public function __construct(TaskStorage $taskStorage)
    {
        $this->taskStorage = $taskStorage;
    }

    /**
     * @param array<string> $taskIds
     *
     * @return array
     */
    public function getPlaceholdersByIds(array $taskIds): array
    {
        $manifestCollection = $this->taskStorage->getArrTasksCollection();

        $taskPlaceholders = [];
        foreach ($taskIds as $taskId) {
            $task = $manifestCollection->getTaskById($taskId);
            if ($task !== null) {
                $taskPlaceholders[$taskId] = array_map(
                    fn (array $placeholder): string => $placeholder['name'],
                    $task['placeholders'],
                );

                continue;
            }

            if ($this->taskStorage->getTaskById($taskId)) {
                $taskPlaceholders[$taskId] = array_map(
                    fn (PlaceholderInterface $placeholder): string => $placeholder->getName(),
                    $this->taskStorage->getTaskById($taskId)->getPlaceholders(),
                );
            }
        }

        return $taskPlaceholders;
    }
}
