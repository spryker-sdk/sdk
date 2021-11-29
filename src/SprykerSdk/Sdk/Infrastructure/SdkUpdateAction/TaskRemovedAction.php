<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Contracts\Repository\TaskRemoveRepositoryInterface;
use SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\RemovedEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskRemovedAction implements SdkUpdateActionInterface
{
    protected EventDispatcherInterface $eventDispatcher;

    protected TaskRemoveRepositoryInterface $taskRemoveRepository;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TaskRemoveRepositoryInterface $taskRemoveRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->taskRemoveRepository = $taskRemoveRepository;
    }

    /**
     * @param string[] $taskIds
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return void
     */
    public function apply(array $taskIds, array $folderTasks, array $databaseTasks): void
    {
        foreach ($taskIds as $taskId) {
            $databaseTask = $databaseTasks[$taskId];

            $this->taskRemoveRepository->remove($databaseTask);

            $this->eventDispatcher->dispatch(new RemovedEvent($databaseTask), RemovedEvent::NAME);
        }
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $folderTasks
     * @param \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[] $databaseTasks
     *
     * @return string[]
     */
    public function filter(array $folderTasks, array $databaseTasks): array
    {
        $taskIds = [];

        foreach ($databaseTasks as $databaseTask) {
            if (!isset($folderTasks[$databaseTask->getId()])) {
                $taskIds[] = $databaseTask->getId();
            }
        }

        return $taskIds;
    }
}
