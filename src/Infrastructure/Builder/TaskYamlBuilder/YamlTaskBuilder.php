<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class YamlTaskBuilder implements ApplicableTaskBuilderInterface
{
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
        return new Task(
            '',
            '',
            [],
            new Lifecycle(
                new InitializedEventData(),
                new UpdatedEventData(),
                new RemovedEventData(),
            ),
            '',
        );
    }
}
