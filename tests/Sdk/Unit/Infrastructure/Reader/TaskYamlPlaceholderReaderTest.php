<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Reader;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\InitializedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\Lifecycle;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\RemovedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\UpdatedEventData;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Entity\Task;
use SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlPlaceholderReader;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Reader
 * @group TaskYamlPlaceholderReaderTest
 * Add your own group annotations below this line
 */
class TaskYamlPlaceholderReaderTest extends Unit
{
    /**
     * @var string
     */
    protected const EXISTING_PLACEHOLDER_NAME = '%one%';

    /**
     * @dataProvider provideRegisteredTasks
     *
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\ManifestCollectionDto $collectionDto
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $task
     *
     * @return void
     */
    public function testGetPlaceholdersByIdsReturnsListOfPlaceholdersIfFound(
        ManifestCollectionDto $collectionDto,
        TaskInterface $task
    ): void {
        // Arrange
        $storage = $this->createMock(TaskStorage::class);
        $storage->expects($this->any())
            ->method('getArrTasksCollection')
            ->willReturn($collectionDto);
        $storage->expects($this->any())
            ->method('getTaskById')
            ->with(static::EXISTING_PLACEHOLDER_NAME)
            ->willReturn($task);
        $reader = new TaskYamlPlaceholderReader($storage);

        // Act
        $placeholders = $reader->getPlaceholdersByIds([static::EXISTING_PLACEHOLDER_NAME]);

        // Assert
        $this->assertCount(
            1,
            $placeholders,
        );
    }

    /**
     * @return array<array>
     */
    public function provideRegisteredTasks(): array
    {
        $collection1 = new ManifestCollectionDto();
        $collection1->addTask(['id' => 'someID', 'name' => static::EXISTING_PLACEHOLDER_NAME]);

        $collection2 = new ManifestCollectionDto();
        $collection2->addTask(['id' => 'someID', 'name' => '%not-existed%']);

        return [
            [
                'collectionDto' => $collection1,
                'task' => new Task(
                    'someID',
                    '',
                    [],
                    new Lifecycle(new InitializedEventData(), new UpdatedEventData(), new RemovedEventData()),
                    '',
                    [new Placeholder('%not-existed%', '')],
                ),
            ],
            [
                'collectionDto' => $collection2,
                'task' => new Task(
                    'someID',
                    '',
                    [],
                    new Lifecycle(new InitializedEventData(), new UpdatedEventData(), new RemovedEventData()),
                    '',
                    [new Placeholder(static::EXISTING_PLACEHOLDER_NAME, '')],
                ),
            ],
        ];
    }
}
