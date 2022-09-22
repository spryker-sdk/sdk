<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet;

use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\TaskLifecycleInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetCommandsFactory;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetOverrideMapFactory;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetPlaceholdersFactory;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskFromYamlTaskSetBuilder implements TaskFromYamlTaskSetBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetCommandsFactory
     */
    protected TaskSetCommandsFactory $taskSetCommandsFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetPlaceholdersFactory
     */
    protected TaskSetPlaceholdersFactory $taskSetPlaceholdersFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetOverrideMapFactory
     */
    protected TaskSetOverrideMapFactory $taskSetPlaceholdersOverrideMapFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskSetPlaceholdersBuilder
     */
    protected TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskSetCommandsBuilder
     */
    protected TaskSetCommandsBuilder $taskSetCommandsBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetCommandsFactory $taskSetCommandsFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetPlaceholdersFactory $taskSetPlaceholdersFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml\TaskSetOverrideMapFactory $taskSetPlaceholdersOverrideMapFactory
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskSetPlaceholdersBuilder $taskSetPlaceholdersBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskSet\TaskSetCommandsBuilder $taskSetCommandsBuilder
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
     * @param array<string, mixed> $taskConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function buildTaskFromYamlTaskSet(
        array $taskSetConfiguration,
        array $taskConfigurations,
        array $existingTasks
    ): TaskInterface {
        $taskSetOverrideMap = $this->taskSetPlaceholdersOverrideMapFactory->createTaskSetOverrideMap($taskSetConfiguration);

        return new Task(
            $taskSetConfiguration['id'],
            $taskSetConfiguration['short_description'],
            $this->getCommands($taskSetConfiguration, $taskConfigurations, $existingTasks, $taskSetOverrideMap),
            $this->getLifeCycle(),
            $taskSetConfiguration['version'],
            $this->getPlaceholders($taskSetConfiguration, $taskConfigurations, $existingTasks, $taskSetOverrideMap),
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
     * @param array<string, mixed> $taskConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto $taskSetOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function getPlaceholders(
        array $taskSetConfiguration,
        array $taskConfigurations,
        array $existingTasks,
        TaskSetOverrideMapDto $taskSetOverrideMap
    ): array {
        $subTasksPlaceholders = $this->taskSetPlaceholdersFactory->getSubTasksPlaceholders(
            $taskSetConfiguration,
            $taskConfigurations,
            $existingTasks,
        );

        return $this->taskSetPlaceholdersBuilder->buildTaskSetPlaceholders($subTasksPlaceholders, $taskSetOverrideMap);
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, mixed> $taskConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto $taskSetOverrideMap
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function getCommands(
        array $taskSetConfiguration,
        array $taskConfigurations,
        array $existingTasks,
        TaskSetOverrideMapDto $taskSetOverrideMap
    ): array {
        $subTasksCommands = $this->taskSetCommandsFactory->getSubTasksCommands(
            $taskSetConfiguration,
            $taskConfigurations,
            $existingTasks,
        );

        return $this->taskSetCommandsBuilder->buildTaskSetCommands($subTasksCommands, $taskSetOverrideMap);
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\TaskLifecycleInterface
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
