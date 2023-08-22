<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Mapper;

use Codeception\Test\Unit;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Domain\Entity\TaskSetTaskRelation as DomainTaskSetTaskRelation;
use SprykerSdk\Sdk\Infrastructure\Mapper\TaskSetTaskRelationMapper;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Mapper
 * @group TaskSetTaskRelationMapperTest
 * Add your own group annotations below this line
 */
class TaskSetTaskRelationMapperTest extends Unit
{
    /**
     * @return void
     */
    public function testMapToInfrastructureTaskSetRelationShouldThrowExceptionWhenTaskSetNotFound(): void
    {
        // Arrange
        $taskSetId = 'test:task-set';
        $taskId = 'test:task';
        $repositoryMock = $this->createObjectRepositoryMock($taskSetId, $taskId, null, $this->createTaskMock($taskId));
        $taskSetTaskRelationMapper = new TaskSetTaskRelationMapper($repositoryMock);
        $domainRelation = new DomainTaskSetTaskRelation($this->createTaskMock($taskSetId), $this->createTaskMock($taskId));

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskSetTaskRelationMapper->mapToInfrastructureTaskSetRelation($domainRelation);
    }

    /**
     * @return void
     */
    public function testMapToInfrastructureTaskSetRelationShouldThrowExceptionWhenSubTaskNotFound(): void
    {
        // Arrange
        $taskSetId = 'test:task-set';
        $taskId = 'test:task';
        $repositoryMock = $this->createObjectRepositoryMock($taskSetId, $taskId, $this->createTaskMock($taskSetId), null);
        $taskSetTaskRelationMapper = new TaskSetTaskRelationMapper($repositoryMock);
        $domainRelation = new DomainTaskSetTaskRelation($this->createTaskMock($taskSetId), $this->createTaskMock($taskId));

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $taskSetTaskRelationMapper->mapToInfrastructureTaskSetRelation($domainRelation);
    }

    /**
     * @return void
     */
    public function testMapToInfrastructureTaskSetRelationShouldMapToInfrastructureRelation(): void
    {
        // Arrange
        $taskSetId = 'test:task-set';
        $taskId = 'test:task';
        $infrastructureTaskSet = $this->createTaskMock($taskSetId);
        $infrastructureSubTask = $this->createTaskMock($taskId);
        $repositoryMock = $this->createObjectRepositoryMock($taskSetId, $taskId, $infrastructureTaskSet, $infrastructureSubTask);
        $taskSetTaskRelationMapper = new TaskSetTaskRelationMapper($repositoryMock);
        $domainRelation = new DomainTaskSetTaskRelation($this->createTaskMock($taskSetId), $this->createTaskMock($taskId));

        // Act
        $infrastructureRelation = $taskSetTaskRelationMapper->mapToInfrastructureTaskSetRelation($domainRelation);

        // Assert
        $this->assertSame($infrastructureTaskSet, $infrastructureRelation->getTaskSet());
        $this->assertSame($infrastructureSubTask, $infrastructureRelation->getSubTask());
    }

    /**
     * @param string $taskSetId
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface|null $taskSet
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface|null $subTask
     *
     * @return \Doctrine\Persistence\ObjectRepository
     */
    protected function createObjectRepositoryMock(
        string $taskSetId,
        string $taskId,
        ?TaskInterface $taskSet = null,
        ?TaskInterface $subTask = null
    ): ObjectRepository {
        $repository = $this->createMock(ObjectRepository::class);
        $repository->method('find')->willReturnMap([
            [$taskSetId, $taskSet],
            [$taskId, $subTask],
        ]);

        return $repository;
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(string $taskId): TaskInterface
    {
        $task = $this->createMock(TaskInterface::class);
        $task->method('getId')->willReturn($taskId);

        return $task;
    }
}
