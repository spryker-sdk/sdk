<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Validator\Json;

use JsonSchema\Validator;
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

        $error = array_pop($errors);

        return [
            'detail' => sprintf('The property "%s" is required', $error['property']),
            'code' => Response::HTTP_BAD_REQUEST,
            'status' => (string)Response::HTTP_BAD_REQUEST,
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
                'data' => [
                    'type' => 'object',
                    'required' => true,
                    'properties' => [
                        'id' => [
                            'type' => 'string',
                            'required' => true,
                        ],
                        'type' => [
                            'type' => 'string',
                            'required' => true,
                        ],
                        'attributes' => [
                            'type' => 'object',
                            'required' => true,
                        ],
                    ],
                ],
            ],
        ];
    }
}
