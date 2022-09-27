<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\TaskSet\Yaml;

use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory;
use SprykerSdk\SdkContracts\Entity\CommandInterface;

class TaskSetCommandsFactory
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder
     */
    protected ConverterBuilder $converterBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory
     */
    protected CommandFactory $commandFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder $converterBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Factory\CommandFactory $commandFactory
     */
    public function __construct(ConverterBuilder $converterBuilder, CommandFactory $commandFactory)
    {
        $this->converterBuilder = $converterBuilder;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, array<string, mixed>> $tasksConfigurations
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return array<string, array<\SprykerSdk\SdkContracts\Entity\CommandInterface>>
     */
    public function getSubTasksCommands(array $taskSetConfiguration, array $tasksConfigurations, array $existingTasks): array
    {
        $commands = [];

        foreach ($taskSetConfiguration['tasks'] as $task) {
            $commands[(string)$task['id']] = isset($tasksConfigurations[$task['id']])
                ? [$this->createCommandFromArray($tasksConfigurations[$task['id']], $taskSetConfiguration)]
                : $existingTasks[$task['id']]->getCommands();
        }

        return $commands;
    }

    /**
     * @param array<string, mixed> $commandData
     * @param array<string, mixed> $taskSetConfiguration
     *
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandFromArray(array $commandData, array $taskSetConfiguration): CommandInterface
    {
        $converter = $this->converterBuilder->buildConverter(new TaskYaml($commandData, []));

        return $this->commandFactory->createFromArray(
            $commandData,
            false,
            $converter,
            $taskSetConfiguration['error_message'] ?? '',
        );
    }
}
