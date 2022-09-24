<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskRegistry implements TaskRegistryInterface
{
    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $existingTasks = [];

    /**
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     */
    public function __construct(iterable $existingTasks = [])
    {
        foreach ($existingTasks as $existingTask) {
            $this->existingTasks[$existingTask->getId()] = $existingTask;
        }
    }

    /**
     * @return array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getAll(): array
    {
        return $this->existingTasks;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function get(string $id): TaskInterface
    {
        return $this->existingTasks[$id];
    }

    /**
     * @param string $id
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function set(string $id, TaskInterface $task): void
    {
        $this->existingTasks[$id] = $task;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function find(string $id): ?TaskInterface
    {
        return $this->existingTasks[$id] ?? null;
    }
}
