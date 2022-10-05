<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml;

use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class YamlTaskSetBuilder implements ApplicableTaskBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface
     */
    protected TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder
     */
    public function __construct(TaskFromYamlTaskSetBuilderInterface $taskFromYamlTaskSetBuilder)
    {
        $this->taskFromYamlTaskSetBuilder = $taskFromYamlTaskSetBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $taskYamlCriteriaDto
     *
     * @return bool
     */
    public function isApplicable(TaskYamlCriteriaDto $taskYamlCriteriaDto): bool
    {
        return $taskYamlCriteriaDto->getType() === TaskType::TASK_TYPE__TASK_SET;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto $taskYamlCriteriaDto
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function build(TaskYamlCriteriaDto $taskYamlCriteriaDto): TaskInterface
    {
        return $this->taskFromYamlTaskSetBuilder->buildTaskFromYamlTaskSet(
            $taskYamlCriteriaDto->getTaskData(),
            $taskYamlCriteriaDto->getTaskData(),
        );
    }
}
