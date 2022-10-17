<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder;

use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\SdkContracts\Enum\Lifecycle as LifecycleEnum;

class LifecycleTaskPartBuilder implements TaskPartBuilderInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\TaskPartBuilderInterface
     */
    protected TaskPartBuilderInterface $placeholderPartBuilder;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\TaskPartBuilderInterface $placeholderPartBuilder
     */
    public function __construct(TaskPartBuilderInterface $placeholderPartBuilder)
    {
        $this->placeholderPartBuilder = $placeholderPartBuilder;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto $resultDto
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto
     */
    public function addPart(TaskYamlCriteriaDto $criteriaDto, TaskYamlResultDto $resultDto): TaskYamlResultDto
    {
        $lifecycle = new Lifecycle(
            new InitializedEventData(),
            new UpdatedEventData(),
            new RemovedEventData(),
        );

        $lifecycle->setInitializedEventData($this->createInitializedEventData($criteriaDto));
        $lifecycle->setUpdatedEventData($this->createUpdatedEventData($criteriaDto));
        $lifecycle->setRemovedEventData($this->createRemovedEventData($criteriaDto));

        $resultDto->setLifecycle($lifecycle);

        return $resultDto;
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData
     */
    protected function createInitializedEventData(
        TaskYamlCriteriaDto $criteriaDto
    ): LifecycleEventDataInterface {
        $taskData = $criteriaDto->getTaskData();
        if (!isset($taskData['lifecycle'][LifecycleEnum::EVENT_INITIALIZED])) {
            return new InitializedEventData();
        }

        $eventData = $taskData['lifecycle'][LifecycleEnum::EVENT_INITIALIZED];
        $commands = $this->buildCommands($eventData);
        $placeholders = $this->buildPlaceholders($eventData, $criteriaDto);
        $files = $this->buildFiles($eventData);

        return new InitializedEventData($commands, $placeholders, $files);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData
     */
    protected function createUpdatedEventData(
        TaskYamlCriteriaDto $criteriaDto
    ): LifecycleEventDataInterface {
        $taskData = $criteriaDto->getTaskData();
        if (!isset($taskData['lifecycle'][LifecycleEnum::EVENT_UPDATED])) {
            return new UpdatedEventData();
        }

        $eventData = $taskData['lifecycle'][LifecycleEnum::EVENT_UPDATED];
        $commands = $this->buildCommands($eventData);
        $placeholders = $this->buildPlaceholders($eventData, $criteriaDto);
        $files = $this->buildFiles($eventData);

        return new UpdatedEventData($commands, $placeholders, $files);
    }

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData
     */
    protected function createRemovedEventData(
        TaskYamlCriteriaDto $criteriaDto
    ): LifecycleEventDataInterface {
        $taskData = $criteriaDto->getTaskData();
        if (!isset($taskData['lifecycle'][LifecycleEnum::EVENT_REMOVED])) {
            return new RemovedEventData();
        }

        $eventData = $taskData['lifecycle'][LifecycleEnum::EVENT_REMOVED];
        $commands = $this->buildCommands($eventData);
        $placeholders = $this->buildPlaceholders($eventData, $criteriaDto);
        $files = $this->buildFiles($eventData);

        return new RemovedEventData($commands, $placeholders, $files);
    }

    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\CommandInterface>
     */
    protected function buildCommands(array $data): array
    {
        $commands = [];

        if (!isset($data['commands'])) {
            return $commands;
        }

        foreach ($data['commands'] as $command) {
            $commands[] = new Command(
                $command['command'],
                $command['type'],
                false,
            );
        }

        return $commands;
    }

    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\FileInterface>
     */
    protected function buildFiles(array $data): array
    {
        $files = [];

        if (!isset($data['files'])) {
            return $files;
        }

        foreach ($data['files'] as $file) {
            $files[] = new File(
                $file['path'],
                $file['content'],
            );
        }

        return $files;
    }

    /**
     * @param array $placeholdersData
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto $criteriaDto
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\PlaceholderInterface>
     */
    protected function buildPlaceholders(array $placeholdersData, TaskYamlCriteriaDto $criteriaDto): array
    {
        $criteriaDto = new TaskYamlCriteriaDto(
            $criteriaDto->getType(),
            $placeholdersData,
            $criteriaDto->getTaskListData(),
        );

        $resultDto = $this->placeholderPartBuilder->addPart($criteriaDto, new TaskYamlResultDto());

        return $resultDto->getPlaceholders();
    }
}
