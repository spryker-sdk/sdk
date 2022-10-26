<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Validator\Manifest;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlPlaceholderReader;
use SprykerSdk\Sdk\Infrastructure\Storage\TaskStorage;
use SprykerSdk\Sdk\Infrastructure\Validator\Manifest\ManifestEntriesValidator;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Validator
 * @group Manifest
 * @group ManifestEntriesValidatorTest
 * Add your own group annotations below this line
 */
class ManifestEntriesValidatorTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testIsPlaceholderExistsReturnsTrueIfFoundOne(): void
    {
        // Arrange
        $placeholderName = '%pl1%';
        $taskData = [
            'shared_placeholders' => [],
            'tasks' => [
                'id' => [
                    'id' => 'someID',
                    'placeholder_overrides' => [$placeholderName => 'value'],
                ],
            ],
        ];
        $existingPlaceholders = [
            'someID' => [$placeholderName],
        ];

        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $taskStorage = $this->createMock(TaskStorage::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $placeholderReader->expects($this->once())
            ->method('getPlaceholdersByIds')
            ->willReturn($existingPlaceholders);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isExists = $validator->isPlaceholderExists($taskData);

        // Assert
        $this->assertTrue($isExists);
    }

    /**
     * @return void
     */
    public function testIsTaskIdExistReturnsTrueIfTaskExists(): void
    {
        // Arrange
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $taskStorage = $this->createMock(TaskStorage::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);

        $taskStorage->expects($this->once())
            ->method('hasManifestWithId')
            ->willReturn(true);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isTaskIdExist('test');

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsTaskIdExistReturnsTrueIfTaskNotFound(): void
    {
        // Arrange
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $taskStorage = $this->createMock(TaskStorage::class);

        $taskStorage->expects($this->once())
            ->method('hasManifestWithId')
            ->willReturn(false);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isTaskIdExist('test');

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testIsValueResolverNameValidReturnsTrueIfExists(): void
    {
        // Arrange
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $taskStorage = $this->createMock(TaskStorage::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $valueResolverRegistry->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isValueResolverNameValid('test');

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsValueResolverNameValidReturnsFalseIfValueResolverIsNotRegistered(): void
    {
        // Arrange
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $taskStorage = $this->createMock(TaskStorage::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $valueResolverRegistry->expects($this->once())
            ->method('has')
            ->willReturn(false);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isValueResolverNameValid('test');

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testGetSupportedTypesReturnsArrayWithTaggedSupportedTypes(): void
    {
        // Arrange
        $expectedTypes = [
            'one' => true,
            'two' => true,
        ];

        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $taskStorage = $this->createMock(TaskStorage::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            $expectedTypes,
        );

        // Act
        $types = $validator->getSupportedTypes();

        // Assert
        $this->assertSame(
            array_keys($expectedTypes),
            $types,
        );
    }

    /**
     * @return void
     */
    public function testIsCommandStringContainsAllPlaceholdersReturnsTrueIfAllPlaceholdersPresent(): void
    {
        // Arrange
        $command = 'vendor/bin/comparator %left% %right%';
        $placeholders = [
            ['name' => '%left%'],
            ['name' => '%right%'],
        ];
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $taskStorage = $this->createMock(TaskStorage::class);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isCommandStringContainsAllPlaceholders($command, $placeholders);

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsCommandStringContainsAllPlaceholdersReturnsFalseIfPlaceholderObsolete(): void
    {
        // Arrange
        $command = 'vendor/bin/comparator %leftAndRight%';
        $placeholders = [
            ['name' => '%leftAndRight%'],
            ['name' => '%right%'],
        ];
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $taskStorage = $this->createMock(TaskStorage::class);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isCommandStringContainsAllPlaceholders($command, $placeholders);

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @return void
     */
    public function testIsConverterExistsReturnsTrueIfExists(): void
    {
        // Arrange
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $taskStorage = $this->createMock(TaskStorage::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $converterRegistry->expects($this->once())
            ->method('has')
            ->willReturn(true);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isConverterExists('test');

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @return void
     */
    public function testIsConverterExistsReturnsFalseIfValueResolverIsNotRegistered(): void
    {
        // Arrange
        $valueResolverRegistry = $this->createMock(ValueResolverRegistryInterface::class);
        $converterRegistry = $this->createMock(ConverterRegistryInterface::class);
        $taskStorage = $this->createMock(TaskStorage::class);
        $placeholderReader = $this->createMock(TaskYamlPlaceholderReader::class);
        $converterRegistry->expects($this->once())
            ->method('has')
            ->willReturn(false);

        $validator = new ManifestEntriesValidator(
            $valueResolverRegistry,
            $converterRegistry,
            $taskStorage,
            $placeholderReader,
            [],
        );

        // Act
        $isValid = $validator->isConverterExists('test');

        // Assert
        $this->assertFalse($isValid);
    }
}
