<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\TaskInitializerInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\TaskSaveRepositoryInterface;
use SprykerSdk\Sdk\Core\Lifecycle\Event\InitializedEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TaskInitializer implements TaskInitializerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\TaskRepositoryInterface $taskYamlRepository
     * @param \SprykerSdk\Sdk\Core\Domain\Repository\TaskSaveRepositoryInterface $taskSaveRepository
     */
    public function __construct(
        protected TaskRepositoryInterface $taskYamlRepository,
        protected TaskSaveRepositoryInterface $taskSaveRepository,
        protected EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\TaskInterface[]
     */
    public function initialize(): array
    {
        $tasks = $this->taskYamlRepository->findAll();

        $entities = [];

        foreach ($tasks as $task) {
            $entities[] = $this->taskSaveRepository->save($task);

            $this->eventDispatcher->dispatch(new InitializedEvent($task), InitializedEvent::NAME);
        }

        return $entities;
    }
}
