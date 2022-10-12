<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\LifecycleTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\PlaceholderTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\SdkContracts\Entity\Lifecycle\LifecycleInterface;
use SprykerSdk\SdkContracts\Enum\Lifecycle;
use SprykerSdk\SdkContracts\Enum\Task;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskYamlBuilder
 * @group TaskPartBuilder
 * @group LifecyclePartBuilderTest
 */
class LifecyclePartBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testAddPartBuildsFreshLifecycleIfNoLifecycleConfigured(): void
    {
        // Arrange
        $criteriaDto = new TaskYamlCriteriaDto(Task::TASK_TYPE_LOCAL_CLI, [], []);
        $lifecyclePartBuilder = new LifecycleTaskPartBuilder(new PlaceholderTaskPartBuilder(new InMemoryTaskStorage()));

        // Act
        $resultDto = $lifecyclePartBuilder->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $this->assertInstanceOf(
            LifecycleInterface::class,
            $resultDto->getLifecycle(),
        );

        $this->assertInstanceOf(
            LifecycleEventDataInterface::class,
            $resultDto->getLifecycle()->getInitializedEventData(),
        );

        $this->assertInstanceOf(
            LifecycleEventDataInterface::class,
            $resultDto->getLifecycle()->getUpdatedEventData(),
        );

        $this->assertInstanceOf(
            LifecycleEventDataInterface::class,
            $resultDto->getLifecycle()->getRemovedEventData(),
        );
    }

    /**
     * @dataProvider provideLifecycleData
     *
     * @param array $eventData
     *
     * @return void
     */
    public function testAddPartBuildsLifecycleParts(array $eventData): void
    {
        // Arrange
        $taskData = [
            'lifecycle' => [
                Lifecycle::EVENT_INITIALIZED => $eventData,
                Lifecycle::EVENT_UPDATED => $eventData,
                Lifecycle::EVENT_REMOVED => $eventData,
            ],
        ];

        $criteriaDto = new TaskYamlCriteriaDto(
            Task::TASK_TYPE_LOCAL_CLI,
            $taskData,
            [],
        );
        $lifecyclePartBuilder = new LifecycleTaskPartBuilder(new PlaceholderTaskPartBuilder(new InMemoryTaskStorage()));

        // Act
        $resultDto = $lifecyclePartBuilder->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $this->assertCount(
            isset($eventData['commands']) ? count($eventData['commands']) : 0,
            $resultDto->getLifecycle()->getInitializedEventData()->getCommands(),
            'The result dto contains a lifecycle with a provided commands.',
        );

        $this->assertCount(
            isset($eventData['files']) ? count($eventData['files']) : 0,
            $resultDto->getLifecycle()->getInitializedEventData()->getFiles(),
            'The result dto contains a lifecycle with a provided files.',
        );

        $this->assertCount(
            isset($eventData['commands']) ? count($eventData['commands']) : 0,
            $resultDto->getLifecycle()->getUpdatedEventData()->getCommands(),
            'The result dto contains a lifecycle with a provided commands.',
        );

        $this->assertCount(
            isset($eventData['files']) ? count($eventData['files']) : 0,
            $resultDto->getLifecycle()->getUpdatedEventData()->getFiles(),
            'The result dto contains a lifecycle with a provided files.',
        );

        $this->assertCount(
            isset($eventData['commands']) ? count($eventData['commands']) : 0,
            $resultDto->getLifecycle()->getRemovedEventData()->getCommands(),
            'The result dto contains a lifecycle with a provided commands.',
        );

        $this->assertCount(
            isset($eventData['files']) ? count($eventData['files']) : 0,
            $resultDto->getLifecycle()->getRemovedEventData()->getFiles(),
            'The result dto contains a lifecycle with a provided files.',
        );
    }

    /**
     * @return array<array>
     */
    public function provideLifecycleData(): array
    {
        return [
            [
                'eventData' => [],
                'taskListData' => [],
            ],
            [
                'eventData' => [
                    'commands' => [
                        [
                            'command' => 'echo "Hello"',
                            'type' => Task::TASK_TYPE_LOCAL_CLI,
                        ],
                    ],
                    'files' => [
                        [
                            'path' => './test_config.json',
                            'content' => '{}',
                        ],
                    ],
                ],
            ],
        ];
    }
}
