<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Enum\OpenApiType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SdkInitProjectController extends BaseController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return $this->createSuccessResponse(OpenApiType::SDK_INIT_PROJECT, OpenApiType::SDK_INIT_PROJECT, $request->request->all());
    }
}
