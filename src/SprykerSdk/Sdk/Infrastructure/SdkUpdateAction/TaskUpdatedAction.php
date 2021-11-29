<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use Composer\Semver\Comparator;
use SprykerSdk\Sdk\Contracts\Repository\TaskSaveRepositoryInterface;
use SprykerSdk\Sdk\Contracts\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\UpdatedEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskUpdatedAction implements SdkUpdateActionInterface
{
    protected EventDispatcherInterface $eventDispatcher;

    protected TaskSaveRepositoryInterface $taskUpdateRepository;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TaskSaveRepositoryInterface $taskUpdateRepository
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->taskUpdateRepository = $taskUpdateRepository;
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
            $folderTask = $folderTasks[$taskId];
            $databaseTask = $databaseTasks[$taskId];

            $this->taskUpdateRepository->update($folderTask, $databaseTask);

            $this->eventDispatcher->dispatch(new UpdatedEvent($folderTask), UpdatedEvent::NAME);
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

        foreach ($folderTasks as $folderTask) {
            $databaseTask = $databaseTasks[$folderTask->getId()] ?? null;

            if ($databaseTask !== null && Comparator::greaterThan($folderTask->getVersion(), $databaseTask->getVersion())) {
                $taskIds[] = $folderTask->getId();
            }
        }

        return $taskIds;
    }
}
