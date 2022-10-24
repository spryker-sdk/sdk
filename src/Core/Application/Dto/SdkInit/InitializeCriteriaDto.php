<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\SdkInit;

use SprykerSdk\SdkContracts\Entity\TaskInterface;

class InitializeCriteriaDto
{
    /**
     * @var string
     */
    protected string $sourceType = '';

    /**
     * @var array
     */
    protected array $settings = [];

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $taskCollection = [];

    /**
     * @param string $sourceType
     * @param array $settings
     */
    public function __construct(string $sourceType, array $settings = [])
    {
        $this->sourceType = $sourceType;
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     *
     * @return void
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return (bool)$this->settings;
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
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
