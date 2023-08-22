<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskSet;

interface TaskSetTaskRelationsFromYamlBuilderInterface
{
    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>
     */
    public function buildFromYamlTaskSet(array $taskSetConfiguration, array $existingTasks): array;
}
