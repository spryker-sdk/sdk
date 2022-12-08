<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskSet;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation;

class TaskSetTaskRelationsFromYamlBuilder implements TaskSetTaskRelationsFromYamlBuilderInterface
{
    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @throws \InvalidArgumentException
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelationInterface>
     */
    public function buildFromYamlTaskSet(array $taskSetConfiguration, array $existingTasks): array
    {
        if (!isset($existingTasks[$taskSetConfiguration['id']])) {
            throw new InvalidArgumentException(sprintf('Can\'t find loaded task set `%s`', $taskSetConfiguration['id']));
        }

        $relations = [];

        foreach ($taskSetConfiguration['tasks'] as $task) {
            if (!isset($existingTasks[$task['id']])) {
                throw new InvalidArgumentException(sprintf('Can\'t find loaded task `%s`', $task['id']));
            }

            $relations[] = new TaskSetTaskRelation($existingTasks[$taskSetConfiguration['id']], $existingTasks[$task['id']]);
        }

        return $relations;
    }
}
