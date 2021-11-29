<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Contracts\Repository\TaskSaveRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskInitializerInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskInitializer implements TaskInitializerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\TaskSaveRepositoryInterface $taskSaveRepository
     * @param \Symfony\Contracts\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        protected TaskSaveRepositoryInterface $taskSaveRepository,
        protected EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Entity\TaskInterface[]
     */
    public function initialize(array $tasks): array
    {
        $entities = [];

        foreach ($tasks as $task) {
            $entities[] = $this->taskSaveRepository->create($task);

            $this->eventDispatcher->dispatch(new InitializedEvent($task), InitializedEvent::NAME);
        }

        return $entities;
    }
}
