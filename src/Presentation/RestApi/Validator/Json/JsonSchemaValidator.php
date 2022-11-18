<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Validator\Json;

use JsonException;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use SprykerSdk\Sdk\Presentation\RestApi\Exception\InvalidJsonSchemaException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonSchemaValidator
{
    /**
     * @var \JsonSchema\Validator
     */
    protected Validator $validator;

    /**
     * @param \JsonSchema\Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \SprykerSdk\Sdk\Presentation\RestApi\Exception\InvalidJsonSchemaException
     *
     * @return void
     */
    public function validate(Request $request): void
    {
        try {
            $data = json_decode((string)$request->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new InvalidJsonSchemaException(
                ['Invalid request.'],
                Response::HTTP_BAD_REQUEST,
                (string)Response::HTTP_BAD_REQUEST,
            );
        }

        $this->validator->validate($data, $this->getSchema(), Constraint::CHECK_MODE_TYPE_CAST);

        $errors = $this->validator->getErrors();
        if (!$errors) {
            $request->request->replace($data);

            return;
        }

        $details = array_map(fn (array $error): string => $error['message'], $errors);

        throw new InvalidJsonSchemaException(
            $details,
            Response::HTTP_BAD_REQUEST,
            (string)Response::HTTP_BAD_REQUEST,
        );
    }

    /**
     * @return array
     */
    protected function getSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                OpenApiField::DATA => [
                    'type' => 'object',
                    'required' => true,
                    'properties' => [
                        OpenApiField::ID => [
                            'type' => 'string',
                            'required' => true,
                        ],
                        'type' => [
                            OpenApiField::TYPE => 'string',
                            'required' => true,
                        ],
                        OpenApiField::ATTRIBUTES => [
                            'type' => 'object',
                            'required' => true,
                        ],
                    ],
                ],
            ],
        ];
    }
}
