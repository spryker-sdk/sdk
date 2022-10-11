<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\ManifestValidator;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\ManifestEntriesValidator;
use SprykerSdk\Sdk\Infrastructure\ManifestValidator\TaskManifestConfiguration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * @group Unit
 * @group Infrastructure
 * @group ManifestValidator
 * @group TaskManifestConfigurationTest
 */
class TaskManifestConfigurationTest extends Unit
{
    /**
     * @return void
     */
    public function testValidateTaskIsValid(): void
    {
        // Arrange
        $manifestEntriesValidator = $this->createMock(ManifestEntriesValidator::class);
        $manifestEntriesValidator
            ->method('isTaskNameExist')
            ->willReturn(true);
        $manifestEntriesValidator->method('isPlaceholderInStringValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('getSupportedTypes')
            ->willReturn(['array', 'string', 'int']);
        $manifestEntriesValidator->method('isNameValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('isTaskNameExist')
            ->willReturn(true);
        $manifestEntriesValidator->method('isConverterValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('getTaskPlaceholders')
            ->willReturn([]);

        $manifestValidation = new TaskManifestConfiguration($manifestEntriesValidator);
        $taskConfig = Yaml::parseFile(realpath(__DIR__ . '/../../../../_support/data/Task/HelloWorldTask.yaml'));
        $treeBuilder = $manifestValidation->getConfigTreeBuilder($taskConfig);

        // Act
        $result = (new Processor())->process(
            $treeBuilder->buildTree(),
            [$taskConfig],
        );
        // Assert
        $this->assertIsArray($taskConfig);
    }

    /**
     * @return void
     */
    public function testValidateTaskIsNotValid(): void
    {
        // Arrange
        $manifestEntriesValidator = $this->createMock(ManifestEntriesValidator::class);
        $manifestEntriesValidator
            ->method('isTaskNameExist')
            ->willReturn(true);
        $manifestEntriesValidator->method('isPlaceholderInStringValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('getSupportedTypes')
            ->willReturn(['array', 'string', 'int']);
        $manifestEntriesValidator->method('isNameValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('isTaskNameExist')
            ->willReturn(true);
        $manifestEntriesValidator->method('isConverterValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('getTaskPlaceholders')
            ->willReturn([]);

        $manifestValidation = new TaskManifestConfiguration($manifestEntriesValidator);
        $taskConfig = Yaml::parseFile(realpath(__DIR__ . '/../../../../_support/data/WrongTask/HelloWorldTask.yaml'));
        $treeBuilder = $manifestValidation->getConfigTreeBuilder($taskConfig);

        // Assert
        $this->expectException(InvalidConfigurationException::class);

        // Act
        $result = (new Processor())->process(
            $treeBuilder->buildTree(),
            [$taskConfig],
        );
    }
}
