<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Creator;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Creator\TaskCreator;
use SprykerSdk\Sdk\Core\Application\Dto\SdkInit\InitializeCriteriaDto;
use SprykerSdk\Sdk\Core\Domain\Enum\CallSource;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskFromTaskSetBuilderInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Creator
 * @group TaskCreatorTest
 */
class TaskCreatorTest extends Unit
{
    /**
     * @var string
     */
    protected const EXISTENT_TASK_ID = '1';

    /**
     * @var string
     */
    protected const NON_EXISTENT_TASK_ID = '2';

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testCreateTasksIfTaskExists(): void
    {
        //Arrange
        $task = $this->tester->createTask(['id' => static::EXISTENT_TASK_ID]);

        $taskRepositoryMock = $this->createRepositoryMock($task);

        $taskSetBuilderMock = $this->createMock(TaskFromTaskSetBuilderInterface::class);
        $taskSetBuilderMock->expects($this->never())
            ->method('buildTaskFromTaskSet');

        $taskCreator = new TaskCreator(
            $taskRepositoryMock,
            $taskSetBuilderMock,
        );

        $dto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_CLI, []);
        $dto->addTask($task);

        //Act
        $resultDto = $taskCreator->createTasks($dto);

        //Assert
        $this->assertCount(0, $resultDto->getTaskCollection());
    }

    /**
     * @return void
     */
    public function testCreateTasksIfTaskNotExist(): void
    {
        //Arrange
        $task = $this->tester->createTask(['id' => static::NON_EXISTENT_TASK_ID]);
        $taskRepositoryMock = $this->createRepositoryMock($task, 'create');
        $taskSetBuilderMock = $this->createMock(TaskFromTaskSetBuilderInterface::class);
        $taskSetBuilderMock->expects($this->never())
            ->method('buildTaskFromTaskSet');

        $taskCreator = new TaskCreator(
            $taskRepositoryMock,
            $taskSetBuilderMock,
        );

        $dto = new InitializeCriteriaDto(CallSource::SOURCE_TYPE_CLI, []);
        $dto->addTask($task);

        //Act
        $resultDto = $taskCreator->createTasks($dto);

        //Assert
        $this->assertCount(1, $resultDto->getTaskCollection());
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     * @param string|null $expectedMethodName
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Repository\TaskRepository
     */
    protected function createRepositoryMock(TaskInterface $task, ?string $expectedMethodName = null): TaskRepository
    {
        $repositoryMock = $this->createMock(TaskRepository::class);
        $repositoryMock->expects($this->any())
            ->method('findById')
            ->willReturnMap([[static::EXISTENT_TASK_ID, $task], [static::NON_EXISTENT_TASK_ID, null]]);

        if ($expectedMethodName === null) {
            return $repositoryMock;
        }

        $repositoryMock
            ->expects($this->once())
            ->method($expectedMethodName)
            ->with($this->equalTo($task));

        return $repositoryMock;
    }
}
