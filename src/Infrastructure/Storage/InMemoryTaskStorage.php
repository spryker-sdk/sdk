<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Storage;

use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class InMemoryTaskStorage
{
    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $yamlTaskCollection = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $phpTaskCollection = [];

    /**
     * @var array<\SprykerSdk\SdkContracts\Entity\TaskSetInterface>
     */
    protected array $taskSetCollection = [];

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getYamlTaskCollection(): array
    {
        return $this->yamlTaskCollection;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function getYamlTaskById(string $id): ?TaskInterface
    {
        return $this->yamlTaskCollection[$id] ?? null;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $yamlTask
     *
     * @return $this
     */
    public function addYamlTask(TaskInterface $yamlTask)
    {
        $this->yamlTaskCollection[$yamlTask->getId()] = $yamlTask;

        return $this;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    public function getPhpTaskCollection(): array
    {
        return $this->phpTaskCollection;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface|null
     */
    public function getPhpTaskById(string $id): ?TaskInterface
    {
        return $this->phpTaskCollection[$id] ?? null;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $phpTask
     *
     * @return $this
     */
    public function addPhpTask(TaskInterface $phpTask)
    {
        $this->phpTaskCollection[$phpTask->getId()] = $phpTask;

        return $this;
    }

    /**
     * @return array<\SprykerSdk\SdkContracts\Entity\TaskSetInterface>
     */
    public function getTaskSetCollection(): array
    {
        return $this->taskSetCollection;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface|null
     */
    public function getTaskSetById(string $id): ?TaskSetInterface
    {
        return $this->taskSetCollection[$id] ?? null;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     *
     * @return $this
     */
    public function addTaskSet(TaskSetInterface $taskSet)
    {
        $this->taskSetCollection[$taskSet->getId()] = $taskSet;

        return $this;
    }
}
