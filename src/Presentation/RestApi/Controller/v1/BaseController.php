<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory
     */
    protected ResponseFactory $responseFactory;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Factory\ResponseFactory $responseFactory
     *
     * @return void
     */
    public function setResponseFactory(ResponseFactory $responseFactory): void
    {
        $this->responseFactory = $responseFactory;
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
}
