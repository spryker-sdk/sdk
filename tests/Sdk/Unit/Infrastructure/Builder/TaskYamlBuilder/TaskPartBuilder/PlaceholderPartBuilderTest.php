<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Placeholder;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder\PlaceholderPartBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

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
            TaskType::TASK_TYPE__LOCAL_CLI,
            [],
            [],
        );

        // Act
        $resultDto = (new PlaceholderPartBuilder(new InMemoryTaskStorage()))->addPart($criteriaDto, new TaskYamlResultDto());

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
        $resultDto = (new PlaceholderPartBuilder(new InMemoryTaskStorage()))->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $placeholder = $taskListData[static::TASK_SET_ID]['placeholders'][0];
        $name = $placeholder instanceof PlaceholderInterface ? $placeholder->getName() : $placeholder['name'];
        $this->assertSame(
            $name,
            $resultDto->getPlaceholders()[static::TASK_SET_PLACEHOLDER_NAME]->getName(),
            'The result dto contains placeholder from the given Task List',
        );
    }

    /**
     * @return array<array>
     */
    public function provideTaskPlaceholdersForSuccessTest(): array
    {
        return [
            [
                'type' => TaskType::TASK_TYPE__TASK_SET,
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
                'type' => TaskType::TASK_TYPE__TASK_SET,
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
