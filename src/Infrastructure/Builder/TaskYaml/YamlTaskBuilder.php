<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class YamlTaskBuilder implements ApplicableTaskBuilderInterface
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\TaskPartBuilderInterface> $taskPartBuilders
     */
    protected iterable $taskPartBuilders;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\TaskPartBuilderInterface> $taskPartBuilders
     */
    public function __construct(iterable $taskPartBuilders)
    {
        $this->taskPartBuilders = $taskPartBuilders;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $taskYamlCriteriaDto
     *
     * @return bool
     */
    public function isApplicable(TaskYamlCriteriaDto $taskYamlCriteriaDto): bool
    {
        return in_array(
            $taskYamlCriteriaDto->getType(),
            [TaskType::TASK_TYPE__LOCAL_CLI, TaskType::TASK_TYPE__LOCAL_CLI_INTERACTIVE],
            true,
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $taskYamlCriteriaDto
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function build(TaskYamlCriteriaDto $taskYamlCriteriaDto): TaskInterface
    {
        $resultTaskDto = new TaskYamlResultDto();
        foreach ($this->taskPartBuilders as $taskPartBuilder) {
            $resultTaskDto = $taskPartBuilder->addPart($taskYamlCriteriaDto, $resultTaskDto);
        }

        return $this->createTaskByTaskYamlResultDto($resultTaskDto);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskByTaskYamlResultDto(
        TaskYamlResultDto $resultDto
    ): TaskInterface {
        /** @var \SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface $lifecycle */
        $lifecycle = $resultDto->getLifecycle();

        return new Task(
            $resultDto->getScalarPart('id', ''),
            $resultDto->getScalarPart('short_description', ''),
            $resultDto->getCommands(),
            $lifecycle,
            $resultDto->getScalarPart('version', ''),
            $resultDto->getPlaceholders(),
            $resultDto->getScalarPart('help', ''),
            $resultDto->getScalarPart('successor', ''),
            $resultDto->getScalarPart('deprecated', false),
            $resultDto->getScalarPart('stage', ''),
            $resultDto->getScalarPart('optional', true),
            $resultDto->getScalarPart('stages', ''),
        );
    }
}
