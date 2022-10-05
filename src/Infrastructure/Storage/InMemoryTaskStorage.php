<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Storage;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

class InMemoryTaskStorage
{
    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $taskCollection = [];

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getTaskCollection(): array
    {
        return $this->taskCollection;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function getTaskById(string $id): ?TaskInterface
    {
        return $this->taskCollection[$id] ?? null;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return $this
     */
    public function addTask(TaskInterface $task)
    {
        $this->taskCollection[$task->getId()] = $task;

        return $this;
    }
}
