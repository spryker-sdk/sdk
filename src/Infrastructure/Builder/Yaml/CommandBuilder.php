<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Service\TaskPool;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
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
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskPool $taskPool
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilderInterface $converterBuilder
     */
    public function __construct(TaskPool $taskPool, ConverterBuilderInterface $converterBuilder)
    {
        $this->taskPool = $taskPool;
        $this->converterBuilder = $converterBuilder;
    }

    /**
     * @param array $data
     * @param array $taskListData
     * @param array<string> $tags
     *
     * @return array<int, \SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    public function buildCommands(array $data, array $taskListData, array $tags = []): array
    {
        $commands = [];

        if (in_array($data['type'], ['local_cli', 'local_cli_interactive'], true)) {
            $commands[] = $this->buildCommand(
                $data,
                $data['tags'] ?? [],
                false,
                $this->converterBuilder->buildConverter($data),
            );
        }

        $taskSetCommands = $this->buildTaskSetCommands($data, $taskListData, $tags);

        return array_merge($commands, $taskSetCommands);
    }

    /**
     * @param array $data
     * @param array $taskListData
     * @param array $tags
     *
     * @return array
     */
    protected function buildTaskSetCommands(array $data, array $taskListData, array $tags = []): array
    {
        $commands = [];

        if ($data['type'] !== TaskType::TASK_SET_TYPE) {
            return $commands;
        }

        foreach ($data['tasks'] as $task) {
            $tasksTags = $task['tags'] ?? [];
            if ($tags && !array_intersect($tags, $tasksTags)) {
                continue;
            }
            $taskData = $taskListData[$task['id']] ?? $this->taskPool->getTasks()[$task['id']];

            if ($taskData instanceof TaskInterface) {
                foreach ($taskData->getCommands() as $command) {
                    $commands[] = $command;
                }

                continue;
            }

            $commands[] = $this->buildCommand(
                $taskData,
                $tasksTags,
                $task['stop_on_error'],
                $this->converterBuilder->buildConverter($taskData),
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
