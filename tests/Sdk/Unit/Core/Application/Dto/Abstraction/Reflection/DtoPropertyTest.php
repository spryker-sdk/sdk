<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Dto\Abstraction\Reflection;

use Codeception\Test\Unit;
use LogicException;
use SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Dto;
use SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Reflection\DtoClass;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Dto
 * @group Abstraction
 * @group Reflection
 * @group DtoPropertyTest
 * Add your own group annotations below this line
 */
class DtoPropertyTest extends Unit
{
    /**
     * @return void
     */
    public function testUnsupportedDtoProperty(): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var $unsupportedBareProperty
             */
            public $unsupportedBareProperty;
        };
        $dtoClass = new DtoClass($dto);

        // Assert
        $this->expectException(LogicException::class);

        // Act
        $dtoClass->getProperties();
    }

    /**
     * @return void
     */
    public function testNullableDtoProperty(): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var int|null
             */
            public ?int $nullableIntProperty;
        };
        $dtoClass = new DtoClass($dto);

        // Act
        $nullableIntProperty = $dtoClass->getProperty('nullableIntProperty');

        // Assert
        $this->assertFalse($nullableIntProperty->isRequired());
    }

    /**
     * @return void
     */
    public function testDtoPropertyWithDefaultValue(): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var int|null
             */
            public ?int $nullableIntPropertyWithDefaultValue = 1;
        };
        $dtoClass = new DtoClass($dto);

        // Act
        $nullableIntPropertyWithDefaultValue = $dtoClass->getProperty('nullableIntPropertyWithDefaultValue');

        // Assert
        $this->assertSame($nullableIntPropertyWithDefaultValue->getDefaultValue(), 1);
    }

    /**
     * @return void
     */
    public function testDtoArrayProperty(): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var array
             */
            public array $arrayProperty;

            /**
             * @var array<string>
             */
            public array $typedArrayProperty = [];

            /**
             * @var array<string, bool>
             */
            public array $arrayWithTypedKeysProperty;

            /**
             * @var array<string, array<bool>>|null
             */
            public ?array $nullableArrayWithNestedTypedKeysProperty;
        };
        $dtoClass = new DtoClass($dto);

        // Act
        $arrayProperty = $dtoClass->getProperty('arrayProperty');
        $typedArrayProperty = $dtoClass->getProperty('typedArrayProperty');
        $arrayWithTypedKeysProperty = $dtoClass->getProperty('arrayWithTypedKeysProperty');
        $nullableArrayWithNestedTypedKeysProperty = $dtoClass->getProperty('nullableArrayWithNestedTypedKeysProperty');

        // Assert
        $this->assertSame($arrayProperty->getType(), 'mixed');
        $this->assertTrue($arrayProperty->isArray());
        $this->assertNull($arrayProperty->getDefaultValue());
        $this->assertEquals(['int'], $arrayProperty->getKeyType());

        $this->assertSame($typedArrayProperty->getType(), 'string');
        $this->assertSame([], $typedArrayProperty->getDefaultValue());
        $this->assertEquals(['int'], $typedArrayProperty->getKeyType());

        $this->assertSame($arrayWithTypedKeysProperty->getType(), 'bool');
        $this->assertEquals(['string'], $arrayWithTypedKeysProperty->getKeyType());

        $this->assertSame($nullableArrayWithNestedTypedKeysProperty->getType(), 'bool');
        $this->assertFalse($nullableArrayWithNestedTypedKeysProperty->isRequired());
        $this->assertEquals(['string', 'int'], $nullableArrayWithNestedTypedKeysProperty->getKeyType());
    }
}
