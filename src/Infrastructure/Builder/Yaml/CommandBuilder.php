<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
use SprykerSdk\Sdk\Core\Application\Service\TaskPool;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Enum\CommandType;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ConverterInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class CommandBuilder implements CommandBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\TaskPool
     */
    protected TaskPool $taskPool;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilderInterface
     */
    protected ConverterBuilderInterface $converterBuilder;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface
     */
    protected TaskYamlFactoryInterface $taskYamlFactory;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskPool $taskPool
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilderInterface $converterBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\TaskYamlFactoryInterface $taskYamlFactory
     */
    public function __construct(
        TaskPool $taskPool,
        ConverterBuilderInterface $converterBuilder,
        TaskYamlFactoryInterface $taskYamlFactory
    ) {
        $this->taskPool = $taskPool;
        $this->converterBuilder = $converterBuilder;
        $this->taskYamlFactory = $taskYamlFactory;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return array<int, \SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function buildCommands(TaskYamlInterface $taskYaml): array
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
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface|null
     */
    protected function buildTaskCommand(TaskYamlInterface $taskYaml): ?CommandInterface
    {
        $data = $taskYaml->getTaskData();
        if (!in_array($data['type'], CommandType::LOCAL_CLI_TYPES, true)) {
            return null;
        }

        return $this->buildCommand(
            $data,
            $data['tags'] ?? [],
            false,
            $this->converterBuilder->buildConverter($taskYaml),
        );
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function buildTaskSetCommands(TaskYamlInterface $taskYaml): array
    {
        $data = $taskYaml->getTaskData();
        $taskListData = $taskYaml->getTaskListData();
        $commands = [];

        if ($data['type'] !== TaskType::TASK_SET_TYPE) {
            return $commands;
        }

        foreach ($data['tasks'] as $task) {
            $taskData = $taskListData[$task['id']] ?? $this->taskPool->getNotNestedTaskSet($task['id']);

            if ($taskData instanceof TaskInterface) {
                foreach ($taskData->getCommands() as $command) {
                    $commands[] = $command;
                }

                continue;
            }

            $commands[] = $this->buildCommand(
                $taskData,
                $task['tags'] ?? [],
                $task['stop_on_error'],
                $this->converterBuilder->buildConverter($this->taskYamlFactory->createTaskYaml($taskData, $taskListData)),
            );
        }

        return $commands;
    }

    /**
     * @param array $taskData
     * @param array $tasksTags
     * @param bool $stopOnError
     * @param \SprykerSdk\SdkContracts\Entity\ConverterInterface|null $converter
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Command
     */
    protected function buildCommand(
        array $taskData,
        array $tasksTags,
        bool $stopOnError,
        ?ConverterInterface $converter
    ): Command {
        return new Command(
            $taskData['command'],
            $taskData['type'],
            $stopOnError,
            $tasksTags,
            $converter,
            $taskData['stage'] ?? ContextInterface::DEFAULT_STAGE,
            $taskData['error_message'] ?? '',
        );
    }
}
