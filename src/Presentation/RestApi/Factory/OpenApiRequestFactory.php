<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Factory;

use SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest;
use Symfony\Component\HttpFoundation\Request;

class OpenApiRequestFactory
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \SprykerSdk\Sdk\Presentation\RestApi\OpenApi\OpenApiRequest
     */
    public function createFromRequest(Request $request): OpenApiRequest
    {
        return new OpenApiRequest($request);
    }
}
