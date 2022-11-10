<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Validator\Json;

use JsonSchema\Validator;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
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
     * @param array $request
     *
     * @return array
     */
    public function validate(array $request): array
    {
        $request = json_decode((string)json_encode($request), false);

        $this->validator->validate($request, $this->getSchema());

        $errors = $this->validator->getErrors();
        if (!$errors) {
            return [];
        }

        $details = array_map(fn (array $error): string => $error['message'], $errors);

        return [
            OpenApiField::DETAILS => $details,
            OpenApiField::CODE => Response::HTTP_BAD_REQUEST,
            OpenApiField::STATUS => (string)Response::HTTP_BAD_REQUEST,
        ];
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
