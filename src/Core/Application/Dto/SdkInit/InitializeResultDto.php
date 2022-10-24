<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\SdkInit;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

class InitializeResultDto
{
    /**
     * @var bool
     */
    protected bool $isSuccessful = true;

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $taskCollection = [];

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $taskCollection
     * @param bool $isSuccessful
     */
    public function __construct(array $taskCollection = [], bool $isSuccessful = true)
    {
        $this->taskCollection = $taskCollection;
        $this->isSuccessful = $isSuccessful;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    /**
     * @return void
     */
    public function success(): void
    {
        $this->isSuccessful = true;
    }

    /**
     * @return void
     */
    public function fail()
    {
        $this->isSuccessful = false;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getTaskCollection(): array
    {
        return $this->taskCollection;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $taskCollection
     *
     * @return void
     */
    public function setTaskCollection(array $taskCollection): void
    {
        foreach ($taskCollection as $task) {
            $this->addTask($task);
        }
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function addTask(TaskInterface $task): void
    {
        $this->taskCollection[$task->getId()] = $task;
    }
}
