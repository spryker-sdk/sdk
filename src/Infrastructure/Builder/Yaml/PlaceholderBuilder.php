<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class PlaceholderBuilder
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface
     */
    protected TaskRegistryInterface $taskRegistry;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface
     */
    protected TaskValidatorInterface $nestedTaskSetValidator;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory
     */
    protected PlaceholderFactory $placeholderFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface $taskRegistry
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface $nestedTaskSetValidator
     * @param \SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory $placeholderFactory
     */
    public function __construct(
        TaskRegistryInterface $taskRegistry,
        TaskValidatorInterface $nestedTaskSetValidator,
        PlaceholderFactory $placeholderFactory
    ) {
        $this->taskRegistry = $taskRegistry;
        $this->nestedTaskSetValidator = $nestedTaskSetValidator;
        $this->placeholderFactory = $placeholderFactory;
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

            $placeholders[$placeholderData['name']] = $this->placeholderFactory->createFromArray($placeholderData);
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

        if (!isset($data['type']) || $data['type'] !== TaskType::TYPE_TASK_SET) {
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
}
