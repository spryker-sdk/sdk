<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Validator;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Validator\ConverterInputDataValidator;

/**
 * @group YamlTaskLoading
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Validator
 * @group ConverterInputDataValidatorTest
 */
class ConverterInputDataValidatorTest extends Unit
{
    /**
     * @return void
     */
    public function testIsValidReturnsTrueIfInputDataValid(): void
    {
        // Arrange
        $inputData = [
            'report_converter' => [
                'name' => 'test_converter',
                'configuration' => [],
            ],
        ];

        // Act
        $isValid = (new ConverterInputDataValidator())->isValid($inputData);

        // Assert
        $this->assertTrue($isValid);
    }

    /**
     * @dataProvider provideInvalidInputData
     *
     * @param array $inputData
     *
     * @return void
     */
    public function testIsValidReturnsFalseIfInputDataInvalid(array $inputData): void
    {
        // Act
        $isValid = (new ConverterInputDataValidator())->isValid($inputData);

        // Assert
        $this->assertFalse($isValid);
    }

    /**
     * @return array
     */
    public function provideInvalidInputData(): array
    {
        return [
            [[
                'report_converter' => [
                    'name' => false,
                    'configuration' => [],
                ],
            ]],
            [[
                'report_converter' => [
                    'configuration' => [],
                ],
            ]],
            [[
                'report_converter' => null,
            ]],
            [[
                'report_converter' => [
                    'name' => 'test_converter',
                    'configuration' => 'str',
                ],
            ]],
            [[]],
            [[
                'report_converter' => [
                    'name' => 'test_converter',
                ],
            ]],
        ];
    }
}
