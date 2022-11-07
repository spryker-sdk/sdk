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
     * @param string $id
     * @param string $type
     * @param array $attributes
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function buildResponse(string $id, string $type, array $attributes = []): JsonResponse
    {
        return (new ResponseBuilder())->buildResponse($id, $type, $attributes);
    }

    /**
     * @param string $detail
     * @param int $code
     * @param string $status
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function buildErrorResponse(string $detail, int $code, string $status): JsonResponse
    {
        return (new ResponseBuilder())->buildErrorResponse($detail, $code, $status);
    }
}
