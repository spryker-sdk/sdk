<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskSetCommandPartBuilder extends CommandPartBuilder
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\TaskSetNestingException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto
     */
    public function addPart(
        TaskYamlCriteriaDto $criteriaDto,
        TaskYamlResultDto $resultDto
    ): TaskYamlResultDto {
        if ($criteriaDto->getType() !== TaskType::TASK_TYPE__TASK_SET) {
            return $resultDto;
        }

        foreach ($criteriaDto->getTaskData()['tasks'] as $task) {
            $taskData = $criteriaDto->getTaskListData()[$task['id']] ?? $this->storage->getYamlTaskById($task['id']);

            if ($taskData instanceof TaskSetInterface) {
                throw new TaskSetNestingException('Task set can\'t have another task set inside.');
            }

            if ($taskData instanceof TaskInterface) {
                $this->addCommandByTaskInterface($taskData, $resultDto);

                continue;
            }

            $converter = $this->createConverter($taskData);
            $resultDto->addCommand($this->createCommand($taskData, $converter));
        }

        return $resultDto;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @return void
     */
    protected function addCommandByTaskInterface(TaskInterface $task, TaskYamlResultDto $resultDto): void
    {
        foreach ($task->getCommands() as $command) {
            $resultDto->addCommand($command);
        }
    }
}
