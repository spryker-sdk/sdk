<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder
     */
    protected ResponseBuilder $responseBuilder;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Builder\ResponseBuilder $responseBuilder
     *
     * @return void
     */
    public function setResponseBuilder(ResponseBuilder $responseBuilder): void
    {
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param string $id
     * @param string $type
     * @param array $attributes
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createSuccessResponse(string $id, string $type, array $attributes = []): JsonResponse
    {
        return $this->responseBuilder->createSuccessResponse($id, $type, $attributes);
    }

    /**
     * @param array<string> $details
     * @param int $code
     * @param string $status
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createErrorResponse(array $details, int $code, string $status): JsonResponse
    {
        return $this->responseBuilder->createErrorResponse($details, $code, $status);
    }
}
