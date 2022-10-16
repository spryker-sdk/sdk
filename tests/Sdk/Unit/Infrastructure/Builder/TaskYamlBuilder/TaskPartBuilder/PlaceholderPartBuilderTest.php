<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYaml\TaskPartBuilder\PlaceholderTaskPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;
use SprykerSdk\SdkContracts\Enum\Task;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskYamlBuilder
 * @group TaskPartBuilder
 * @group PlaceholderPartBuilderTest
 */
class PlaceholderPartBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const TASK_SET_ID = 'test:action:entity';

    /**
     * @var string
     */
    protected const TASK_SET_PLACEHOLDER_NAME = '%test%';

    /**
     * @return void
     */
    public function testAddPartDoesNothingIfNoPlaceholdersProvided(): void
    {
        // Arrange
        $criteriaDto = new TaskYamlCriteriaDto(
            Task::TYPE_LOCAL_CLI,
            [],
            [],
        );

        // Act
        $resultDto = (new PlaceholderTaskPartBuilder(new TaskStorage()))->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $this->assertSame([], $resultDto->getPlaceholders());
    }

    /**
     * @dataProvider provideTaskPlaceholdersForSuccessTest
     *
     * @param string $type
     * @param array $taskData
     * @param array $taskListData
     *
     * @return void
     */
    public function testAddPartBuildsPlaceholdersForTaskSetSubTasksIfSuchPresent(
        string $type,
        array $taskData,
        array $taskListData
    ): void {
        // Arrange
        $criteriaDto = new TaskYamlCriteriaDto(
            $type,
            $taskData,
            $taskListData,
        );

        // Act
        $resultDto = (new PlaceholderTaskPartBuilder(new TaskStorage()))->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $placeholder = $taskListData[static::TASK_SET_ID]['placeholders'][0];
        $name = $placeholder instanceof PlaceholderInterface ? $placeholder->getName() : $placeholder['name'];
        $this->assertSame(
            $name,
            $resultDto->getPlaceholders()[static::TASK_SET_PLACEHOLDER_NAME]->getName(),
            'The result dto contains placeholder from the given TaskYaml List',
        );
    }

    /**
     * @return array<array>
     */
    public function provideTaskPlaceholdersForSuccessTest(): array
    {
        return [
            [
                'type' => Task::TYPE_TASK_SET,
                'taskData' => [
                    'tasks' => [
                        [
                            'id' => static::TASK_SET_ID,
                        ],
                    ],
                ],
                'taskListData' => [
                    static::TASK_SET_ID => [
                        'placeholders' => [
                            [
                                'name' => static::TASK_SET_PLACEHOLDER_NAME,
                                'value_resolver' => 'STATIC',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => Task::TYPE_TASK_SET,
                'taskData' => [
                    'tasks' => [
                        [
                            'id' => static::TASK_SET_ID,
                        ],
                    ],
                ],
                'taskListData' => [
                    static::TASK_SET_ID => [
                        'placeholders' => [
                            new Placeholder(
                                static::TASK_SET_PLACEHOLDER_NAME,
                                'STATIC',
                                [],
                                false,
                            ),
                        ],
                    ],
                ],
            ],
        ];
    }
}
