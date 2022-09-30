<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Enum\TaskType;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskYamlBuilder\TaskPartBuilder\PlaceholderBuilderPart;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlCriteriaDto;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYamlResultDto;
use SprykerSdk\Sdk\Infrastructure\Storage\InMemoryTaskStorage;

class PlaceholderPartBuilderTest extends Unit
{
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
        $resultDto = (new PlaceholderBuilderPart(new InMemoryTaskStorage()))->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $this->assertSame([], $resultDto->getPlaceholders());
    }

    /**
     * @todo Provide data provider and use objects as long as arrays
     * @todo create an additional test to check storage logic as well
     *
     * @return void
     */
    public function testAddPartBuildsPlaceholdersForTaskSetSubTasksIfSuchPresent(): void
    {
        // Arrange
        $taskId = 'test:action:entity';
        $placeholderName = '%test%';
        $taskData = [
            'type' => TaskType::TASK_TYPE__TASK_SET,
            'tasks' => [
                [
                    'id' => $taskId,
                ],
            ],
        ];
        $taskListData = [
            $taskId => [
                'placeholders' => [
                    [
                        'name' => $placeholderName,
                        'value_resolver' => 'STATIC',
                    ],
                ],
            ],
        ];

        $criteriaDto = new TaskYamlCriteriaDto(
            $taskData['type'],
            $taskData,
            $taskListData,
        );

        // Act
        $resultDto = (new PlaceholderBuilderPart(new InMemoryTaskStorage()))->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $this->assertSame(
            $taskListData[$taskId]['placeholders'][0]['name'],
            $resultDto->getPlaceholders()[$placeholderName]->getName(),
            'The result dto contains placeholder from the given Task List'
        );
    }
}
