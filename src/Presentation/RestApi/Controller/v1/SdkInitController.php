<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Adapter\Initializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SdkInitController extends AbstractController
{
    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Adapter\Initializer $initializer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function init(Initializer $initializer, Request $request): JsonResponse
    {
        $initialized = $initializer->initialize($request->query->all());

        return $this->json(['result' => $initialized ? 'Ok' : 'Failed']);
    }
}
