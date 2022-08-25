<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\ErrorCommandInterface;
use SprykerSdk\SdkContracts\Entity\ExecutableCommandInterface;
use SprykerSdk\SdkContracts\Entity\StagedTaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class DynamicTaskSetCreator
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\TaskOptionBuilder
     */
    protected TaskOptionBuilder $taskOptionBuilder;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface>
     */
    protected array $tasks = [];

    /**
     * @var array<string, array<\Symfony\Component\Console\Input\InputOption>>
     */
    protected array $taskOptions = [];

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \SprykerSdk\Sdk\Infrastructure\Service\TaskOptionBuilder $taskOptionBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskRepositoryInterface $taskRepository
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        TaskOptionBuilder $taskOptionBuilder,
        TaskRepositoryInterface $taskRepository
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->taskOptionBuilder = $taskOptionBuilder;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param string $taskSettingKey
     *
     * @return array<\Symfony\Component\Console\Input\InputOption>
     */
    public function getTaskOptions(string $taskSettingKey): array
    {
        if (!isset($this->taskOptions[$taskSettingKey])) {
            $this->taskOptions[$taskSettingKey] = $this->taskOptionBuilder->extractOptions($this->getTask($taskSettingKey));
        }

        return $this->taskOptions[$taskSettingKey];
    }

    /**
     * @param string $taskSettingKey
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    public function getTask(string $taskSettingKey): TaskInterface
    {
        if (!isset($this->tasks[$taskSettingKey])) {
            /** @var array<string> $taskIds */
            $taskIds = $this->projectSettingRepository->getOneByPath($taskSettingKey)->getValues();
            $this->tasks[$taskSettingKey] = $this->fillTask($taskSettingKey, $this->taskRepository->findByIds($taskIds));
        }

        return $this->tasks[$taskSettingKey];
    }

    /**
     * @param string $taskSettingKey
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function fillTask(string $taskSettingKey, array $tasks): TaskInterface
    {
        return new Task(
            $taskSettingKey,
            $taskSettingKey,
            $this->getCommands($tasks),
            (new Lifecycle(
                new InitializedEventData(),
                new UpdatedEventData(),
                new RemovedEventData(),
            )),
            '',
            $this->getPlaceholders($tasks),
            '',
            null,
            false,
            ContextInterface::DEFAULT_STAGE,
            false,
            [],
        );
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function getPlaceholders(array $tasks): array
    {
        $placeholders = [];

        foreach ($tasks as $task) {
            $placeholders[] = $task->getPlaceholders();
        }

        $placeholders = array_merge(...$placeholders);

        $uniquePlaceholders = [];

        /** @var \SprykerSdk\SdkContracts\Entity\PlaceholderInterface $placeholder */
        foreach ($placeholders as $placeholder) {
            $uniquePlaceholders[$placeholder->getName()] = $placeholder;
        }

        return $uniquePlaceholders;
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\TaskInterface> $tasks
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function getCommands(array $tasks): array
    {
        $commands = [];
        foreach ($tasks as $task) {
            foreach ($task->getCommands() as $command) {
                $commands[] = new Command(
                    $command instanceof ExecutableCommandInterface || $command->getType() === 'php' ?
                        get_class($command) :
                        $command->getCommand(),
                    $command->getType(),
                    false,
                    $command->getTags(),
                    $command->getConverter(),
                    $task instanceof StagedTaskInterface ? $task->getStage() : $command->getStage(),
                    $command instanceof ErrorCommandInterface ? $command->getErrorMessage() : '',
                );
            }
        }

        return $commands;
    }
}
