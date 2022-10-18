<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Validator\Manifest;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Validator\Manifest\ManifestEntriesValidator;
use SprykerSdk\Sdk\Infrastructure\Validator\Manifest\TaskManifestConfiguration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Validator
 * @group Manifest
 * @group TaskManifestConfigurationTest
 * Add your own group annotations below this line
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
            ->method('isTaskIdExist')
            ->willReturn(true);
        $manifestEntriesValidator->method('isCommandStringContainsAllPlaceholders')
            ->willReturn(true);
        $manifestEntriesValidator->method('getSupportedTypes')
            ->willReturn(['array', 'string', 'int']);
        $manifestEntriesValidator->method('isValueResolverNameValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('isConverterExists')
            ->willReturn(true);
        $manifestEntriesValidator->method('isPlaceholderExists')
            ->willReturn(true);

        $manifestValidation = new TaskManifestConfiguration($manifestEntriesValidator);
        $taskConfig = Yaml::parseFile(realpath(__DIR__ . '/../../../../../_support/data/Task/HelloWorldTask.yaml'));
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
            ->method('isTaskIdExist')
            ->willReturn(true);
        $manifestEntriesValidator->method('isCommandStringContainsAllPlaceholders')
            ->willReturn(true);
        $manifestEntriesValidator->method('getSupportedTypes')
            ->willReturn(['array', 'string', 'int']);
        $manifestEntriesValidator->method('isValueResolverNameValid')
            ->willReturn(true);
        $manifestEntriesValidator->method('isConverterExists')
            ->willReturn(true);
        $manifestEntriesValidator->method('isPlaceholderExists')
            ->willReturn(true);

        $manifestValidation = new TaskManifestConfiguration($manifestEntriesValidator);
        $taskConfig = Yaml::parseFile(realpath(__DIR__ . '/../../../../../_support/data/WrongTask/HelloWorldTask.yaml'));
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
