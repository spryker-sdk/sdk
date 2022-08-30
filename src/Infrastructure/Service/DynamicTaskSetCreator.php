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
use SprykerSdk\Sdk\Infrastructure\Entity\Placeholder;
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
        [$commands, $placeholders] = $this->getExtractAndReplaceEqualPlaceholders($tasks);

        return new Task(
            $taskSettingKey,
            $taskSettingKey,
            $commands,
            (new Lifecycle(
                new InitializedEventData(),
                new UpdatedEventData(),
                new RemovedEventData(),
            )),
            '',
            $placeholders,
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
     * @return array
     */
    protected function getExtractAndReplaceEqualPlaceholders(array $tasks): array
    {
        $placeholders = [];
        $commands = [];

        $equalPlaceholderCounter = [];
        foreach ($tasks as $task) {
            $placeholdersForChanging = [];
            foreach ($task->getPlaceholders() as $placeholder) {
                if (
                    isset($placeholders[$placeholder->getName()]) &&
                    (
                        !empty($placeholder->getConfiguration()['defaultValue']) ||
                        empty($placeholder->getConfiguration()['settingPaths'])
                    )
                ) {
                    if (!isset($equalPlaceholderCounter[$placeholder->getName()])) {
                        $equalPlaceholderCounter[$placeholder->getName()] = 0;
                    }
                    $equalPlaceholderCounter[$placeholder->getName()]++;
                    $configuration = $placeholder->getConfiguration();
                    if (isset($configuration['name'])) {
                        $configuration['name'] = sprintf('%s%s', $configuration['name'], $equalPlaceholderCounter[$placeholder->getName()]);
                    }
                    if (isset($configuration['description'])) {
                        $configuration['description'] = sprintf('%s (%s)', $configuration['description'], $task->getId());
                    }

                    $name = $placeholder->getName();

                    $newName = mb_substr($name, 0, -1) . $equalPlaceholderCounter[$placeholder->getName()] . mb_substr($name, -1);

                    $placeholdersForChanging[$name] = $newName;

                    $placeholder = new Placeholder(
                        $newName,
                        $placeholder->getValueResolver(),
                        $configuration,
                        $placeholder->isOptional(),
                    );
                }

                $placeholders[$placeholder->getName()] = $placeholder;
            }

            foreach ($task->getCommands() as $command) {
                if ($command instanceof ExecutableCommandInterface) {
                    $commands[] = $command;

                    continue;
                }
                $commands[] = new Command(
                    $this->replacePlaceholdersInCommand($command->getCommand(), $placeholdersForChanging),
                    $command->getType(),
                    false,
                    $command->getTags(),
                    $command->getConverter(),
                    $task instanceof StagedTaskInterface ? $task->getStage() : $command->getStage(),
                    $command instanceof ErrorCommandInterface ? $command->getErrorMessage() : '',
                );
            }
        }

        return [$commands, $placeholders];
    }

    /**
     * @param string $command
     * @param array $placeholdersForChanging
     *
     * @return string
     */
    protected function replacePlaceholdersInCommand(string $command, array $placeholdersForChanging): string
    {
        $placeholders = array_map(function ($placeholder): string {
            return '/' . preg_quote((string)$placeholder, '/') . '/';
        }, array_keys($placeholdersForChanging));

        $newPlaceholders = array_map(function ($value): string {
            return is_array($value) ? implode(',', $value) : (string)$value;
        }, array_values($placeholdersForChanging));

        return (string)preg_replace($placeholders, $newPlaceholders, $command);
    }
}
