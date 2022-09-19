<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class PlaceholderBuilder implements PlaceholderBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface
     */
    protected TaskRegistryInterface $taskRegistry;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface
     */
    protected TaskValidatorInterface $nestedTaskSetValidator;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface $taskRegistry
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface $nestedTaskSetValidator
     */
    public function __construct(TaskRegistryInterface $taskRegistry, TaskValidatorInterface $nestedTaskSetValidator)
    {
        $this->taskRegistry = $taskRegistry;
        $this->nestedTaskSetValidator = $nestedTaskSetValidator;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    public function buildPlaceholders(TaskYaml $taskYaml): array
    {
        $data = $taskYaml->getTaskData();
        $placeholders = [];
        $taskPlaceholders = [];
        $taskPlaceholders[] = $data['placeholders'] ?? [];

        $taskPlaceholders = $this->extractPlaceholdersFromYamlTasks($taskYaml, $taskPlaceholders);
        $taskPlaceholders = array_merge(...$taskPlaceholders);

        foreach ($taskPlaceholders as $placeholderData) {
            if ($placeholderData instanceof PlaceholderInterface) {
                $placeholders[$placeholderData->getName()] = $placeholderData;

                continue;
            }

            $placeholders[$placeholderData['name']] = $this->createPlaceholder($placeholderData);
        }

        return $placeholders;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     * @param array<string> $taskPlaceholders
     *
     * @return array
     */
    protected function extractPlaceholdersFromYamlTasks(TaskYaml $taskYaml, array $taskPlaceholders): array
    {
        $data = $taskYaml->getTaskData();

        if (!isset($data['type']) || $data['type'] !== TaskType::TASK_SET_TYPE) {
            return $taskPlaceholders;
        }

        $taskListData = $taskYaml->getTaskListData();

        foreach ($data['tasks'] as $task) {
            $taskPlaceholders[] = isset($taskListData[$task['id']]) ?
                $taskListData[$task['id']]['placeholders'] :
                $this->getTaskAndValidate($task['id'])->getPlaceholders();
        }

        return $taskPlaceholders;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function getTaskAndValidate(string $id): TaskInterface
    {
        $taskFromRegistry = $this->taskRegistry->get($id);
        $this->nestedTaskSetValidator->validate($taskFromRegistry);

        return $taskFromRegistry;
    }

    /**
     * @param array $placeholderData
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholder(array $placeholderData): PlaceholderInterface
    {
        return new Placeholder(
            $placeholderData['name'],
            $placeholderData['value_resolver'],
            $placeholderData['configuration'] ?? [],
            $placeholderData['optional'] ?? false,
        );
    }
}
