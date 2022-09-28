<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Core\Domain\Enum\YamlTaskType;
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
        return $taskYamlCriteriaDto->getType() === YamlTaskType::TYPE_TASK;
    }

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
            ''
        );
    }
}
