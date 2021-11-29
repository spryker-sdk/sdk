<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\LifecycleManagerInterface;

class LifecycleManager implements LifecycleManagerInterface
{
    protected TaskRepositoryInterface $taskYamlRepository;

    protected TaskRepositoryInterface $taskEntityRepository;

    /**
     * @var \SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface[]
     */
    protected iterable $actions;

    /**
     * @param TaskRepositoryInterface $taskYamlRepository
     * @param TaskRepositoryInterface $taskEntityRepository
     * @param iterable<\SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface> $actions
     */
    public function __construct(
        TaskRepositoryInterface $taskYamlRepository,
        TaskRepositoryInterface $taskEntityRepository,
        iterable $actions
    ) {
        $this->taskYamlRepository = $taskYamlRepository;
        $this->taskEntityRepository = $taskEntityRepository;
        $this->actions = $actions;
    }

    /**
     * @return void
     */
    public function update(): void
    {
        $folderTasks = $this->taskYamlRepository->findAll();
        $databaseTasks = $this->taskEntityRepository->findAll();

        foreach ($this->actions as $action) {
            $taskIds = $action->filter($folderTasks, $databaseTasks);

            $action->apply($taskIds, $folderTasks, $databaseTasks);
        }
    }
}
