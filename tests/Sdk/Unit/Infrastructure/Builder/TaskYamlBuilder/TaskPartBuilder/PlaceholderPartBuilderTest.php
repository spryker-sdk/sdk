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
        $resultDto = (new PlaceholderBuilderPart())->addPart($criteriaDto, new TaskYamlResultDto());

        // Assert
        $this->assertSame([], $resultDto->getPlaceholders());
    }

    /**
     * @return void
     */
    public function testAddPart(): void
    {
        // Arrange

        // Act

        // Assert
        
    }
}
