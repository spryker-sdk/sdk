<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        return new JsonResponse(['message' => 'Hi! SDK is working on Web server.']);
    }
}
