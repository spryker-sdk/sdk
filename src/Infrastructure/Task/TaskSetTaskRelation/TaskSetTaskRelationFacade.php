<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Task\TaskSetTaskRelation;

use SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsFromYamlBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskSetTaskRelationStorage;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskSetTaskRelationFacade implements TaskSetTaskRelationFacadeInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Storage\TaskSetTaskRelationStorage
     */
    protected TaskSetTaskRelationStorage $taskSetTaskRelationStorage;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsBuilderInterface
     */
    protected TaskSetTaskRelationsBuilderInterface $taskSetTaskRelationsBuilder;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsFromYamlBuilderInterface
     */
    protected TaskSetTaskRelationsFromYamlBuilderInterface $taskSetTaskRelationsFromYamlBuilder;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface
     */
    protected TaskSetTaskRelationRepositoryInterface $taskSetTaskRelationRepository;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Storage\TaskSetTaskRelationStorage $taskSetTaskRelationStorage
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsBuilderInterface $taskSetTaskRelationsBuilder
     * @param \SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetTaskRelationsFromYamlBuilderInterface $taskSetTaskRelationsFromYamlBuilder
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\TaskSetTaskRelationRepositoryInterface $taskSetTaskRelationRepository
     */
    public function __construct(
        TaskSetTaskRelationStorage $taskSetTaskRelationStorage,
        TaskSetTaskRelationsBuilderInterface $taskSetTaskRelationsBuilder,
        TaskSetTaskRelationsFromYamlBuilderInterface $taskSetTaskRelationsFromYamlBuilder,
        TaskSetTaskRelationRepositoryInterface $taskSetTaskRelationRepository
    ) {
        $this->taskSetTaskRelationStorage = $taskSetTaskRelationStorage;
        $this->taskSetTaskRelationsBuilder = $taskSetTaskRelationsBuilder;
        $this->taskSetTaskRelationsFromYamlBuilder = $taskSetTaskRelationsFromYamlBuilder;
        $this->taskSetTaskRelationRepository = $taskSetTaskRelationRepository;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskSetInterface $taskSet
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return void
     */
    public function collectTaskSet(TaskSetInterface $taskSet, array $existingTasks): void
    {
        $this->taskSetTaskRelationStorage->addTaskSetTasRelations(
            $this->taskSetTaskRelationsBuilder->buildFromTaskSet($taskSet, $existingTasks),
        );
    }

    /**
     * @param array<string, mixed> $taskSetConfiguration
     * @param array<string, \SprykerSdk\SdkContracts\Entity\TaskInterface> $existingTasks
     *
     * @return void
     */
    public function collectYamlTaskSet(array $taskSetConfiguration, array $existingTasks): void
    {
        $this->taskSetTaskRelationStorage->addTaskSetTasRelations(
            $this->taskSetTaskRelationsFromYamlBuilder->buildFromYamlTaskSet($taskSetConfiguration, $existingTasks),
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function createRelations(TaskInterface $task): void
    {
        $taskSetTaskRelations = $this->taskSetTaskRelationStorage->getTaskSetTaskRelations($task->getId());
        $this->taskSetTaskRelationRepository->createMany($taskSetTaskRelations);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function removeRelations(TaskInterface $task): void
    {
        $this->taskSetTaskRelationRepository->removeByTaskSetId($task->getId());
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function updateRelations(TaskInterface $task): void
    {
        $this->removeRelations($task);
        $this->createRelations($task);
    }
}
