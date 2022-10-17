<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Storage;

use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskStorage
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    protected ManifestCollectionDto $arrTasksCollection;

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $taskCollection = [];

    /**
     * @param iterable<\SprykerSdk\SdkContracts\Entity\TaskInterface> $phpTasks
     */
    public function __construct(iterable $phpTasks = [])
    {
        foreach ($phpTasks as $phpTask) {
            $this->addTask($phpTask);
        }

        $this->arrTasksCollection = new ManifestCollectionDto();
    }

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
     * @return void
     */
    public function addTask(TaskInterface $task): void
    {
        $this->taskCollection[$task->getId()] = $task;
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto
     */
    public function getArrTasksCollection(): ManifestCollectionDto
    {
        return $this->arrTasksCollection;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function hasManifestWithId(string $id): bool
    {
        return $this->arrTasksCollection->hasTask($id)
            || $this->arrTasksCollection->hasTaskSet($id)
            || $this->getTaskById($id);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto $arrTasksCollection
     * `
     *
     * @return void
     */
    public function setArrTasksCollection(ManifestCollectionDto $arrTasksCollection): void
    {
        $this->arrTasksCollection = $arrTasksCollection;
    }
}
