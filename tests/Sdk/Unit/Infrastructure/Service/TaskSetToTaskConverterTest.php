<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Command;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Extension\ValueResolver\SdkDirectoryValueResolver;
use SprykerSdk\Sdk\Infrastructure\Service\TaskSetToTaskConverter;
use SprykerSdk\SdkContracts\Entity\TaskInterface;
use SprykerSdk\SdkContracts\Entity\TaskSetInterface;

class TaskSetToTaskConverterTest extends Unit
{
    /**
     * @return void
     */
    public function testConvertsTaskSetIntoTask(): void
    {
        // Arrange
        $taskA = $this->createTaskMock('taskA', false, ['tag_a'], '%some_placeholder%');
        $taskB = $this->createTaskMock('taskB', true, ['tag_b'], '%some_placeholder%');
        $taskSet = $this->createTaskSetMock([$taskA, $taskB], ['taskA' => ['a_tag'], 'taskB' => ['b_tag']], ['taskA' => true, 'taskB' => false]);
        $taskSetToTaskConverter = new TaskSetToTaskConverter();

        // Act
        $task = $taskSetToTaskConverter->convert($taskSet);

        // Assert
        $this->assertCount(1, $task->getPlaceholders());
        $this->assertSame('%some_placeholder%', $task->getPlaceholders()[0]->getName());
        $this->assertCount(2, $task->getCommands());
        $this->assertTrue($task->getCommands()[0]->hasStopOnError());
        $this->assertFalse($task->getCommands()[1]->hasStopOnError());
        $this->assertSame(['a_tag'], $task->getCommands()[0]->getTags());
        $this->assertSame(['b_tag'], $task->getCommands()[1]->getTags());
    }

    /**
     * @param array $subTasks
     * @param array $tagsMap
     * @param array $stopOnErrorMap
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskSetInterface
     */
    protected function createTaskSetMock(array $subTasks, array $tagsMap, array $stopOnErrorMap): TaskSetInterface
    {
        $taskSetMock = $this->createMock(TaskSetInterface::class);
        $taskSetMock->method('getSubTasks')->willReturn($subTasks);
        $taskSetMock->method('getSubTasksTagsMap')->willReturn($tagsMap);
        $taskSetMock->method('getSubTasksStopOnErrorMap')->willReturn($stopOnErrorMap);

        return $taskSetMock;
    }

    /**
     * @param string $taskId
     * @param bool $hasStopOnError
     * @param array $tags
     * @param string $placeHolder
     *
     * @return \SprykerSdk\SdkContracts\Entity\TaskInterface
     */
    protected function createTaskMock(string $taskId, bool $hasStopOnError, array $tags, string $placeHolder): TaskInterface
    {
        $taskMock = $this->createMock(TaskInterface::class);
        $taskMock->method('getCommands')->willReturn([
            new Command(
                'some command',
                'local_cli_interactive',
                $hasStopOnError,
                $tags,
            ),
        ]);
        $taskMock->method('getPlaceholders')->willReturn([
            new Placeholder(
                $placeHolder,
                SdkDirectoryValueResolver::class,
                [],
                true,
            ),
        ]);

        $taskMock->method('getId')->willReturn($taskId);

        return $taskMock;
    }
}
