<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Enum\CommandType;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface;
use SprykerSdk\Sdk\Infrastructure\Validator\NestedTaskSetValidator;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class CommandBuilder
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface
     */
    protected TaskRegistryInterface $taskRegistry;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder
     */
    protected ConverterBuilder $converterBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Validator\NestedTaskSetValidator
     */
    protected NestedTaskSetValidator $nestedTaskSetValidator;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory
     */
    protected CommandFactory $commandFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistryInterface $taskRegistry
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder $converterBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Validator\NestedTaskSetValidator $nestedTaskSetValidator
     * @param \SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory $commandFactory
     */
    public function __construct(
        TaskRegistryInterface $taskRegistry,
        ConverterBuilder $converterBuilder,
        NestedTaskSetValidator $nestedTaskSetValidator,
        CommandFactory $commandFactory
    ) {
        $this->taskRegistry = $taskRegistry;
        $this->converterBuilder = $converterBuilder;
        $this->nestedTaskSetValidator = $nestedTaskSetValidator;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return array<int, \SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function buildCommands(TaskYaml $taskYaml): array
    {
        $commands = [];

        $taskCommand = $this->buildTaskCommand($taskYaml);
        if ($taskCommand) {
            $commands[] = $taskCommand;
        }

        $taskSetCommands = $this->buildTaskSetCommands($taskYaml);

        return array_merge($commands, $taskSetCommands);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface|null
     */
    protected function buildTaskCommand(TaskYaml $taskYaml): ?CommandInterface
    {
        $data = $taskYaml->getTaskData();
        if (!in_array($data['type'], CommandType::LOCAL_CLI_TYPES, true)) {
            return null;
        }

        return $this->commandFactory->createFromArray(
            $data,
            false,
            $this->converterBuilder->buildConverter($taskYaml),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function buildTaskSetCommands(TaskYaml $taskYaml): array
    {
        $data = $taskYaml->getTaskData();
        $taskListData = $taskYaml->getTaskListData();
        $commands = [];

        if ($data['type'] !== TaskType::TYPE_TASK_SET) {
            return $commands;
        }

        foreach ($data['tasks'] as $task) {
            $taskData = $taskListData[$task['id']] ?? $this->getTaskAndValidate($task['id']);

            if ($taskData instanceof TaskInterface) {
                foreach ($taskData->getCommands() as $command) {
                    $commands[] = $command;
                }

                continue;
            }

            $converter = $this->converterBuilder->buildConverter(new TaskYaml($taskData, $taskListData, $taskListData));

            $commands[] = $this->commandFactory->createFromArray(
                $taskData,
                $task['stop_on_error'],
                $converter,
                $taskData['error_message'] ?? '',
            );
        }

        return $commands;
    }

    /**
     * @param string $id
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function getTaskAndValidate(string $id): TaskInterface
    {
        $taskFromRegistry = $this->taskRegistry->get($id);
        $this->nestedTaskSetValidator->isValid($taskFromRegistry);

        return $taskFromRegistry;
    }
}
