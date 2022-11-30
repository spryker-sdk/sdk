<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Presentation\RestApi\Validator\Json;

use Codeception\Test\Unit;
use JsonSchema\Validator;
use SprykerSdk\Sdk\Presentation\RestApi\Exception\InvalidJsonSchemaException;
use SprykerSdk\Sdk\Presentation\RestApi\Validator\Json\JsonSchemaValidator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Presentation
 * @group RestApi
 * @group Validator
 * @group Json
 * @group JsonSchemaValidatorTest
 * Add your own group annotations below this line
 */
class JsonSchemaValidatorTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Validator\Json\JsonSchemaValidator
     */
    protected JsonSchemaValidator $jsonSchemaValidator;

    /**
     * @var \JsonSchema\Validator
     */
    protected Validator $validator;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->validator = $this->createMock(Validator::class);
        $this->jsonSchemaValidator = new JsonSchemaValidator($this->validator);
    }

    /**
     * @return void
     */
    public function testValidateWithValidBodyShouldNotThrowException(): void
    {
        // Arrange
        $this->validator
            ->expects($this->once())
            ->method('validate');

        $this->validator
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn([]);

        $jsonBody = json_encode([
            'id' => 'test',
            'type' => 'test',
            'attributes' => [
                'task' => 'hello',
            ],
        ]);

        $request = new Request([], [], [], [], [], [], $jsonBody);

        // Act
        $this->jsonSchemaValidator->validate($request);
    }

    /**
     * @return void
     */
    public function testValidateWithInvalidBodyShouldThrowException(): void
    {
        // Arrange
        $expectedMessage = 'Property "attributes" is required.';
        $this->validator
            ->expects($this->once())
            ->method('validate');

        $this->validator
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn([
                [
                    'message' => $expectedMessage,
                ],
            ]);

        $jsonBody = json_encode([
            'id' => 'test',
            'type' => 'test',
        ]);

        $request = new Request([], [], [], [], [], [], $jsonBody);

        $this->expectException(InvalidJsonSchemaException::class);
        $this->expectExceptionMessage($expectedMessage);

        // Act
        $this->jsonSchemaValidator->validate($request);
    }
}
