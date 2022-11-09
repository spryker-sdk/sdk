<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Builder;

use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiField;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseBuilder
{
    /**
     * @param string $id
     * @param string $type
     * @param array $attributes
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function buildResponse(string $id, string $type, array $attributes): JsonResponse
    {
        return new JsonResponse([
            OpenApiField::DATA => [
                OpenApiField::ID => $id,
                OpenApiField::TYPE => $type,
                OpenApiField::ATTRIBUTES => $attributes,
            ],
        ]);
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
        return new JsonResponse([
            OpenApiField::DETAIL => $detail,
            OpenApiField::CODE => $code,
            OpenApiField::STATUS => $status,
        ], $code);
    }
}
