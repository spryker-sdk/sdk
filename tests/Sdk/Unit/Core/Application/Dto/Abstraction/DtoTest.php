<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Dto\Abstraction;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Dto;
use SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Reflection\DtoProperty;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Core
 * @group Application
 * @group Dto
 * @group Abstraction
 * @group DtoTest
 * Add your own group annotations below this line
 */
class DtoTest extends Unit
{
    /**
     * @return void
     */
    public function testDtoGetPropertyInvalidArgumentException(): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var int
             */
            protected int $integer;
        };

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $dto::create(['string' => 'text']);
    }

    /**
     * @return void
     */
    public function testDtoCreateInvalidArgumentException(): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var int
             */
            protected int $integer;
        };

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $dto::create(['integer' => 'text']);
    }

    /**
     * @dataProvider createDtoDataDataProvider
     *
     * @param array $dtoData
     *
     * @return void
     */
    public function testDtoCreate(array $dtoData): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var int
             */
            protected int $integer;

            /**
             * @var float
             */
            protected float $double;

            /**
             * @var bool
             */
            protected bool $boolean;

            /**
             * @var array<string, string>
             */
            protected array $arr;

            /**
             * @var self|null
             */
            protected ?self $dto;

            /**
             * @return int
             */
            public function getInteger(): int
            {
                return $this->integer;
            }

            /**
             * @return float
             */
            public function getDouble(): float
            {
                return $this->double;
            }

            /**
             * @return bool
             */
            public function isBoolean(): bool
            {
                return $this->boolean;
            }

            /**
             * @return array<string, string>
             */
            public function getArr(): array
            {
                return $this->arr;
            }

            /**
             * @return self|null
             */
            public function getDto(): ?self
            {
                return $this->dto;
            }
        };

        // Act
        $dto = $dto::create($dtoData);

        // Assert
        $this->assertSame($dtoData['integer'], $dto->getInteger());
        $this->assertSame($dtoData['double'], $dto->getDouble());
        $this->assertSame($dtoData['boolean'], $dto->isBoolean());
        $this->assertSame($dtoData['arr'], $dto->getArr());
        $this->assertInstanceOf(Dto::class, $dto->getDto());
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function createDtoDataDataProvider(): array
    {
        return [
            [[
                'integer' => 1,
                'double' => 1.0,
                'boolean' => true,
                'arr' => ['key' => 'value', 'key1' => 'value1'],
                'dto' => [
                    'integer' => 2,
                    'double' => 2.0,
                    'boolean' => true,
                    'arr' => ['key2' => 'value2', 'key3' => 'value3'],
                    'dto' => null,
                ],
            ]],
        ];
    }

    /**
     * @dataProvider createDtoDataDataProvider
     *
     * @param array $dtoData
     *
     * @return void
     */
    public function testDtoCreationFromArray(array $dtoData): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var int
             */
            protected int $integer;

            /**
             * @var float
             */
            protected float $double;

            /**
             * @var bool
             */
            protected bool $boolean;

            /**
             * @var array<string, string>
             */
            protected array $arr;

            /**
             * @var self|null
             */
            protected ?self $dto;

            /**
             * @return int
             */
            public function getInteger(): int
            {
                return $this->integer;
            }

            /**
             * @return float
             */
            public function getDouble(): float
            {
                return $this->double;
            }

            /**
             * @return bool
             */
            public function isBoolean(): bool
            {
                return $this->boolean;
            }

            /**
             * @return array<string, string>
             */
            public function getArr(): array
            {
                return $this->arr;
            }

            /**
             * @return self|null
             */
            public function getDto(): ?self
            {
                return $this->dto;
            }
        };

        // Act
        $dto = $dto::fromArray($dtoData);

        // Assert
        $this->assertSame($dtoData['integer'], $dto->getInteger());
        $this->assertSame($dtoData['double'], $dto->getDouble());
        $this->assertSame($dtoData['boolean'], $dto->isBoolean());
        $this->assertSame($dtoData['arr'], $dto->getArr());
        $this->assertInstanceOf(Dto::class, $dto->getDto());
    }

    /**
     * @return void
     */
    public function testDtoConversionToArray(): void
    {
        // Arrange
        $dto = new class extends Dto {
            /**
             * @var int
             */
            protected int $integer;

            /**
             * @var string
             */
            protected string $string;

            /**
             * @var array<string, string>
             */
            protected array $arr;

            /**
             * @var self|null
             */
            protected ?self $dto;

            /**
             * @return int
             */
            public function getInteger(): int
            {
                return $this->integer;
            }

            /**
             * @return string
             */
            public function getString(): string
            {
                return $this->string;
            }

            /**
             * @return array<string, string>
             */
            public function getArr(): array
            {
                return $this->arr;
            }

            /**
             * @return self|null
             */
            public function getDto(): ?self
            {
                return $this->dto;
            }
        };
        $dtoData = [
            'integer' => 1,
            'string' => 'string1',
            'arr' => ['key' => 'value', 'key1' => 'value1'],
            'dto' => [
                'integer' => 2,
                'string' => 'string2',
                'arr' => ['key2' => 'value2', 'key3' => 'value3'],
                'dto' => null,
            ],
        ];

        // Act
        $dtoArray = $dto::fromArray($dtoData)->toArray();

        // Assert
        $this->assertSame($dtoData, $dtoArray);
    }

    /**
     * @return void
     */
    public function testNestedDtoConversionToArray(): void
    {
        // Arrange
        $dto = new class ([[
            'key' => new class ('test') extends Dto {
                /**
                 * @var string
                 */
                protected string $data;

                /**
                 * @param string $data
                 */
                public function __construct(string $data)
                {
                    $this->data = $data;
                }
            },
        ]]) extends Dto {
            /**
             * @var array<array<string, \SprykerSdk\Sdk\Core\Application\Dto\Abstraction\Dto>>
             */
            protected array $dtoArray;

            /**
             * @param array $dtoArray
             */
            public function __construct(array $dtoArray)
            {
                $this->dtoArray = $dtoArray;
            }
        };

        // Act
        $dtoArray = $dto->toArray(DtoProperty::TYPE_UNDERSCORED);

        // Assert
        $this->assertSame(['dto_array' => [['key' => ['data' => 'test']]]], $dtoArray);
    }
}
