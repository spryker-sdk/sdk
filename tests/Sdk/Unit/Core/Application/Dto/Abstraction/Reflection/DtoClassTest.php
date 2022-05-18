<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Dto\Abstraction\Reflection;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Dto;
use SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoClass;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Dto
 * @group Abstraction
 * @group Reflection
 * @group DtoClassTest
 */
class DtoClassTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Dto
     */
    protected Dto $dto;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction\Reflection\DtoClass
     */
    protected DtoClass $dtoClass;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->dto = new class extends Dto {
            /**
             * @var int
             */
            protected int $protectedInt;

            /**
             * @var float|null
             */
            protected ?float $protectedNullableFloat;

            /**
             * @var string
             */
            public string $publicString = 'constructor was not called';

            /**
             * @var bool
             */
            private bool $privateBoolean;

            /**
             * @var mixed
             */
            public mixed $publicMixed;

            /**
             * @var array<string, string>
             */
            protected array $protectedArrayOfStrings;

            public function __construct()
            {
                $this->publicString = 'constructor was called';
            }
        };
        $this->dtoClass = new DtoClass($this->dto);
    }

    /**
     * @return void
     */
    public function testDtoCreatedWithoutConstructor(): void
    {
        // Act
        $dto = $this->dtoClass->createInstance();

        // Assert
        $this->assertSame($dto->publicString, 'constructor was not called');
    }

    /**
     * @return void
     */
    public function testPublicAndProtectedPropertiesReturned(): void
    {
        // Act
        $properties = $this->dtoClass->getProperties();

        // Assert
        $this->assertSame(array_keys($properties), [
            'protectedInt',
            'protectedNullableFloat',
            'publicString',
            'publicMixed',
            'protectedArrayOfStrings',
        ]);
    }

    /**
     * @return void
     */
    public function testPropertyCanBeAddressedByUnderscoredName(): void
    {
        // Act
        $property = $this->dtoClass->getProperty('protected_nullable_float');

        // Assert
        $this->assertSame($property->getName(), 'protectedNullableFloat');
    }

    /**
     * @return void
     */
    public function testPropertyNameIsNormalized(): void
    {
        // Act
        $propertyName = $this->dtoClass->getPropertyNameNormalized('protected_nullable_float');

        // Assert
        $this->assertSame($propertyName, 'protectedNullableFloat');
    }
}
