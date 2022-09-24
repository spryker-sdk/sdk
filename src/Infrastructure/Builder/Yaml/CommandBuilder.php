<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Core\Domain\Enum\CommandType;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class CommandBuilder implements CommandBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface
     */
    protected TaskRegistryInterface $taskRegistry;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilderInterface
     */
    protected ConverterBuilderInterface $converterBuilder;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface
     */
    protected TaskYamlFactoryInterface $taskYamlFactory;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface
     */
    protected TaskValidatorInterface $nestedTaskSetValidator;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory
     */
    protected CommandFactory $commandFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskRegistryInterface $taskRegistry
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilderInterface $converterBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface $taskYamlFactory
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskValidatorInterface $nestedTaskSetValidator
     * @param \SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory $commandFactory
     */
    public function __construct(
        TaskRegistryInterface $taskRegistry,
        ConverterBuilderInterface $converterBuilder,
        TaskYamlFactoryInterface $taskYamlFactory,
        TaskValidatorInterface $nestedTaskSetValidator,
        CommandFactory $commandFactory
    ) {
        $this->taskRegistry = $taskRegistry;
        $this->converterBuilder = $converterBuilder;
        $this->taskYamlFactory = $taskYamlFactory;
        $this->nestedTaskSetValidator = $nestedTaskSetValidator;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
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
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
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
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function buildTaskSetCommands(TaskYaml $taskYaml): array
    {
        $data = $taskYaml->getTaskData();
        $taskListData = $taskYaml->getTaskListData();
        $commands = [];

        if ($data['type'] !== TaskType::TASK_SET_TYPE) {
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

            $converter = $this->converterBuilder->buildConverter(
                $this->taskYamlFactory->createTaskYaml($taskData, $taskListData),
            );

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
        $this->nestedTaskSetValidator->validate($taskFromRegistry);

        return $taskFromRegistry;
    }
}
