<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiRequestFactory;
use SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory;
use SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory
     */
    protected OpenApiResponseFactory $responseFactory;

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiRequestFactory
     */
    protected OpenApiRequestFactory $requestFactory;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiResponseFactory $responseFactory
     *
     * @return void
     */
    public function setResponseFactory(OpenApiResponseFactory $responseFactory): void
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Factory\OpenApiRequestFactory $requestFactory
     *
     * @return void
     */
    public function setRequestFactory(OpenApiRequestFactory $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
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
        return $this->responseFactory->createSuccessResponse($id, $type, $attributes);
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
        return $this->responseFactory->createErrorResponse($details, $code, $status);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest
     */
    public function createOpenApiRequest(Request $request): OpenApiRequest
    {
        return $this->requestFactory->createFromRequest($request);
    }
}
