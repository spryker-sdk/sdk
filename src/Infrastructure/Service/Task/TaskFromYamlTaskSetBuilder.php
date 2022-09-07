<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Task;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap;
use SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetCommandsFactory;
use SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetOverrideMapFactory;
use SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetPlaceholdersFactory;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskFromYamlTaskSetBuilder implements TaskFromYamlTaskSetBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetCommandsFactory
     */
    protected TaskSetCommandsFactory $taskSetCommandsFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetPlaceholdersFactory
     */
    protected TaskSetPlaceholdersFactory $taskSetPlaceholdersFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetOverrideMapFactory
     */
    protected TaskSetOverrideMapFactory $taskSetPlaceholdersOverrideMapFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetPlaceholdersBuilder
     */
    protected TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetCommandsBuilder
     */
    protected TaskSetCommandsBuilder $taskSetCommandsBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetCommandsFactory $taskSetCommandsFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetPlaceholdersFactory $taskSetPlaceholdersFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\Yaml\TaskSetOverrideMapFactory $taskSetPlaceholdersOverrideMapFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetCommandsBuilder $taskSetCommandsBuilder
     */
    public function __construct(
        TaskSetCommandsFactory $taskSetCommandsFactory,
        TaskSetPlaceholdersFactory $taskSetPlaceholdersFactory,
        TaskSetOverrideMapFactory $taskSetPlaceholdersOverrideMapFactory,
        TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder,
        TaskSetCommandsBuilder $taskSetCommandsBuilder
    ) {
        $this->taskSetCommandsFactory = $taskSetCommandsFactory;
        $this->taskSetPlaceholdersFactory = $taskSetPlaceholdersFactory;
        $this->taskSetPlaceholdersOverrideMapFactory = $taskSetPlaceholdersOverrideMapFactory;
        $this->taskSetPlaceholdersBuilder = $taskSetPlaceholdersBuilder;
        $this->taskSetCommandsBuilder = $taskSetCommandsBuilder;
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, mixed> $allTasksConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function buildTaskFromYamlTaskSet(array $taskSetConfiguration, array $allTasksConfigurations, array $existingTasks): TaskInterface
    {
        $taskSetOverrideMap = $this->taskSetPlaceholdersOverrideMapFactory->createTaskSetOverrideMap($taskSetConfiguration);

        return new Task(
            $taskSetConfiguration['id'],
            $taskSetConfiguration['short_description'],
            $this->getCommands($taskSetConfiguration, $allTasksConfigurations, $existingTasks, $taskSetOverrideMap),
            $this->getLifeCycle(),
            $taskSetConfiguration['version'],
            $this->getPlaceholders($taskSetConfiguration, $allTasksConfigurations, $existingTasks, $taskSetOverrideMap),
            $taskSetConfiguration['help'] ?? null,
            $taskSetConfiguration['successor'] ?? null,
            $taskSetConfiguration['deprecated'] ?? false,
            $taskSetConfiguration['stage'] ?? ContextInterface::DEFAULT_STAGE,
            isset($taskSetConfiguration['optional']) && $taskSetConfiguration['optional'],
            $taskSetConfiguration['stages'] ?? [],
        );
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, mixed> $allTasksConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap $taskSetOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function getPlaceholders(
        array $taskSetConfiguration,
        array $allTasksConfigurations,
        array $existingTasks,
        TaskSetOverrideMap $taskSetOverrideMap
    ): array {
        $subTasksPlaceholders = $this->taskSetPlaceholdersFactory->getSubTasksPlaceholders(
            $taskSetConfiguration,
            $allTasksConfigurations,
            $existingTasks,
        );

        return $this->taskSetPlaceholdersBuilder->buildTaskSetPlaceholders($subTasksPlaceholders, $taskSetOverrideMap);
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, mixed> $allTasksConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     * @param \SprykerSdk\Sdk\Infrastructure\Service\Task\TaskSetOverrideMap\TaskSetOverrideMap $taskSetOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function getCommands(
        array $taskSetConfiguration,
        array $allTasksConfigurations,
        array $existingTasks,
        TaskSetOverrideMap $taskSetOverrideMap
    ): array {
        $subTasksCommands = $this->taskSetCommandsFactory->getSubTasksCommands(
            $taskSetConfiguration,
            $allTasksConfigurations,
            $existingTasks,
        );

        return $this->taskSetCommandsBuilder->buildTaskSetCommands($subTasksCommands, $taskSetOverrideMap);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\Lifecycle\TaskLifecycleInterface
     */
    protected function getLifeCycle(): TaskLifecycleInterface
    {
        return new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData(),
            new RemovedEventData(),
        );
    }
}
