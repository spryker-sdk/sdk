<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\TaskSet;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Builder\TaskSet\TaskSetPlaceholdersBuilder;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskSetOverrideMapDto;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group TaskSet
 * @group TaskSetPlaceholdersBuilderTest
 */
class TaskSetPlaceholdersBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildTaskSetPlaceholdersShouldOverridePlaceholdersWhenOverridePlaceholdersSet(): void
    {
        // Arrange
        $placeholder = $this->createPlaceholderMock(
            '%placeholder_a%',
            'STATIC_TEXT',
            ['name' => 'name_a', 'description' => 'description_a', 'type' => 'type_a', 'settingPaths' => ['path_a']],
            false,
        );

        $newConfiguration = [
            'name' => 'name_b',
            'description' => 'description_b',
            'type' => 'type_b',
            'settingPaths' => ['path_b'],
        ];

        $overrideMap = new TaskSetOverrideMapDto(
            [],
            [],
            [],
            [
                'taskId' => [
                    '%placeholder_a%' => [
                        'name' => '%placeholder_b%',
                        'value_resolver' => 'PATH',
                        'optional' => true,
                        'configuration' => $newConfiguration,
                    ],
                ],
            ],
        );

        $taskSetPlaceholdersBuilder = new TaskSetPlaceholdersBuilder();

        // Act
        $placeholders = $taskSetPlaceholdersBuilder->buildTaskSetPlaceholders(['taskId' => [$placeholder]], $overrideMap);

        // Assert
        $this->assertCount(1, $placeholders);
        $this->assertSame('%placeholder_b%', $placeholders[0]->getName());
        $this->assertTrue($placeholders[0]->isOptional());
        $this->assertSame('PATH', $placeholders[0]->getValueResolver());
        $this->assertSame($newConfiguration, $placeholders[0]->getConfiguration());
    }

    /**
     * @return void
     */
    public function testBuildTaskSetPlaceholdersShouldOverridePlaceholdersWhenSharedPlaceholdersSet(): void
    {
        // Arrange
        $placeholderA = $this->createPlaceholderMock(
            '%placeholder_a%',
            'STATIC_TEXT',
            ['name' => 'name_a', 'description' => 'description_a', 'type' => 'type_a', 'settingPaths' => ['path_a']],
            false,
        );

        $placeholderB = $this->createPlaceholderMock(
            '%placeholder_a%',
            'STATIC_TEXT',
            ['name' => 'name_a', 'description' => 'description_a', 'type' => 'type_a', 'settingPaths' => ['path_a']],
            false,
        );

        $overrideMap = new TaskSetOverrideMapDto(
            [],
            [],
            ['%placeholder_a%' => ['description' => 'shared_placeholder']],
            [],
        );

        $taskSetPlaceholdersBuilder = new TaskSetPlaceholdersBuilder();

        // Act
        $placeholders = $taskSetPlaceholdersBuilder->buildTaskSetPlaceholders(['taskId' => [$placeholderA, $placeholderB]], $overrideMap);

        // Assert
        $this->assertCount(1, $placeholders);
        $this->assertSame('%placeholder_a%', $placeholders[0]->getName());
        $this->assertSame('shared_placeholder', $placeholders[0]->getConfiguration()['description']);
    }

    /**
     * @param string $name
     * @param string $valueResolver
     * @param array $configuration
     * @param bool $isOptional
     *
     * @return \SprykerSdk\SdkContracts\Entity\PlaceholderInterface
     */
    protected function createPlaceholderMock(string $name, string $valueResolver, array $configuration, bool $isOptional): PlaceholderInterface
    {
        $placeholderMock = $this->createMock(PlaceholderInterface::class);

        $placeholderMock->method('getName')->willReturn($name);
        $placeholderMock->method('getValueResolver')->willReturn($valueResolver);
        $placeholderMock->method('getConfiguration')->willReturn($configuration);
        $placeholderMock->method('isOptional')->willReturn($isOptional);

        return $placeholderMock;
    }
}
