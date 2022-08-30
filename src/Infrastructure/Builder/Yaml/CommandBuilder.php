<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Service\TaskPool;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class CommandBuilder implements CommandBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\TaskPool
     */
    protected TaskPool $taskPool;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\TaskPool $taskPool
     */
    public function __construct(TaskPool $taskPool)
    {
        $this->taskPool = $taskPool;
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
            $converter = isset($data['report_converter']) ? new Converter(
                $data['report_converter']['name'],
                $data['report_converter']['configuration'],
            ) : null;
            $commands[] = new Command(
                $data['command'],
                $data['type'],
                false,
                $data['tags'] ?? [],
                $converter,
                $data['stage'] ?? ContextInterface::DEFAULT_STAGE,
                $data['error_message'] ?? '',
            );
        }

        if ($data['type'] === TaskType::TASK_SET_TYPE) {
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

                $converter = isset($taskData['report_converter']) ? new Converter(
                    $taskData['report_converter']['name'],
                    $taskData['report_converter']['configuration'],
                ) : null;

                $commands[] = new Command(
                    $taskData['command'],
                    $taskData['type'],
                    $task['stop_on_error'],
                    $tasksTags,
                    $converter,
                    $taskData['stage'] ?? ContextInterface::DEFAULT_STAGE,
                    $data['error_message'] ?? '',
                );
            }
        }

        return $commands;
    }
}
