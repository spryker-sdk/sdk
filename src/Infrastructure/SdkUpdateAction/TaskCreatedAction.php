<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\SdkUpdateAction;

use SprykerSdk\Sdk\Core\Application\Dependency\SdkUpdateAction\SdkUpdateActionInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;

class TaskCreatedAction implements SdkUpdateActionInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface
     */
    protected TaskManagerInterface $taskManager;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskManagerInterface $taskManager
     */
    public function __construct(TaskManagerInterface $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     * @param array<string> $taskIds
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return void
     */
    public function apply(array $taskIds, array $tasksFromDirectories, array $tasksFromDatabase): void
    {
        $tasks = [];

        foreach ($taskIds as $taskId) {
            $tasks[] = $tasksFromDirectories[$taskId];
        }

        /**
         * @todo :: source must be dynamic value based on the call source.
         */
        $criteriaDto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_CLI);
        $criteriaDto->setTaskCollection($tasks);

        $this->taskManager->initialize($criteriaDto);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDirectories
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasksFromDatabase
     *
     * @return array<string>
     */
    public function filter(array $tasksFromDirectories, array $tasksFromDatabase): array
    {
        $taskIds = [];

        foreach ($tasksFromDirectories as $tasksFromDirectory) {
            if (!isset($tasksFromDatabase[$tasksFromDirectory->getId()])) {
                $taskIds[] = $tasksFromDirectory->getId();
            }
        }

        return $taskIds;
    }
}
