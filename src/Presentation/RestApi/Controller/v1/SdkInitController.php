<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Adapter\Initializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SdkInitController extends AbstractController
{
    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Adapter\Initializer $initializer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function init(Initializer $initializer): JsonResponse
    {
        $initialized = $initializer->initialize([]);

        return $this->json(['result' => $initialized ? 'Ok' : 'Failed']);
    }
}
