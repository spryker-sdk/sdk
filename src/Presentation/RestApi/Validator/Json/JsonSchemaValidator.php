<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Validator\Json;

use JsonException;
use JsonSchema\Validator;
use SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder;
use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonSchemaValidator
{
    /**
     * @var \JsonSchema\Validator
     */
    protected Validator $validator;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder
     */
    protected ResponseBuilder $responseBuilder;

    /**
     * @param \JsonSchema\Validator $validator
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder $responseBuilder
     */
    public function __construct(Validator $validator, ResponseBuilder $responseBuilder)
    {
        $this->validator = $validator;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|null
     */
    public function validate(Request $request): ?JsonResponse
    {
        try {
            $data = json_decode((string)$request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

            $validationData = json_decode((string)$request->getContent(), false, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            return $this->responseBuilder->buildErrorResponse(
                ['Invalid request.'],
                Response::HTTP_BAD_REQUEST,
                (string)Response::HTTP_BAD_REQUEST,
            );
        }

        $this->validator->validate($validationData, $this->getSchema());

        $errors = $this->validator->getErrors();
        if (!$errors) {
            $request->request->replace($data);

            return null;
        }

        $details = array_map(fn (array $error): string => $error['message'], $errors);

        return $this->responseBuilder->buildErrorResponse(
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
