<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;

class LifecycleManager implements LifecycleManagerInterface
{
    protected TaskRepositoryInterface $taskFileRepository;

    protected TaskRepository $taskEntityRepository;

    /**
     * @var array<\SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction\SdkUpdateActionInterface>
     */
    protected iterable $actions;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\TaskRepositoryInterface $taskFileRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository $taskEntityRepository
     * @param iterable<\SprykerSdk\Sdk\Core\Appplication\Dependency\SdkUpdateAction\SdkUpdateActionInterface> $actions
     */
    public function __construct(
        TaskRepositoryInterface $taskFileRepository,
        TaskRepository $taskEntityRepository,
        iterable $actions
    ) {
        $this->taskFileRepository = $taskFileRepository;
        $this->taskEntityRepository = $taskEntityRepository;
        $this->actions = $actions;
    }

    /**
     * @return void
     */
    public function update(): void
    {
        $folderTasks = $this->taskFileRepository->findAll();
        $databaseTasks = $this->taskEntityRepository->findAllIndexedCollection();

        foreach ($this->actions as $action) {
            $taskIds = $action->filter($folderTasks, $databaseTasks);

            $action->apply($taskIds, $folderTasks, $databaseTasks);
        }
    }
}
