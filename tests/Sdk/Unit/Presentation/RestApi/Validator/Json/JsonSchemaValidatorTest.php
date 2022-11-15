<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Presentation\RestApi\Validator\Json;

use Codeception\Test\Unit;
use JsonSchema\Validator;
use SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder;
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
        $this->jsonSchemaValidator = new JsonSchemaValidator($this->validator, new ResponseBuilder());
    }

    /**
     * @return void
     */
    public function testValidateWithValidBodyShouldReturnNull(): void
    {
        $this->validator
            ->expects($this->once())
            ->method('validate');

        $this->validator
            ->expects($this->once())
            ->method('getErrors')
            ->willReturn([]);

        // Arrange
        $jsonBody = json_encode([
            'id' => 'test',
            'type' => 'test',
            'attributes' => [
                'task' => 'hello',
            ],
        ]);

        $request = new Request([], [], [], [], [], [], $jsonBody);

        // Act
        $errorResponse = $this->jsonSchemaValidator->validate($request);

        // Assert
        $this->assertNull($errorResponse);
    }

    /**
     * @return void
     */
    public function testValidateWithInvalidBodyShouldReturnJsonResponse(): void
    {
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

        // Arrange
        $jsonBody = json_encode([
            'id' => 'test',
            'type' => 'test',
        ]);

        $request = new Request([], [], [], [], [], [], $jsonBody);

        // Act
        $errorResponse = $this->jsonSchemaValidator->validate($request);

        // Assert
        $parsedContent = json_decode($errorResponse->getContent(), true);

        $this->assertSame($expectedMessage, $parsedContent['details'][0]);
    }
}
