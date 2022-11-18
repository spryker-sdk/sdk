<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Presentation\RestApi\Processor\SdkInitProjectProcessor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SdkInitProjectController extends BaseController
{
    /**
     * @var string
     */
    public const TYPE = 'sdk-init-project';

    /**
     * @var \SprykerSdk\Sdk\Presentation\RestApi\Processor\SdkInitProjectProcessor
     */
    protected SdkInitProjectProcessor $sdkInitProjectProcessor;

    /**
     * @param \SprykerSdk\Sdk\Presentation\RestApi\Processor\SdkInitProjectProcessor $sdkInitProjectProcessor
     */
    public function __construct(SdkInitProjectProcessor $sdkInitProjectProcessor)
    {
        $this->sdkInitProjectProcessor = $sdkInitProjectProcessor;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->sdkInitProjectProcessor->process($request);

        return $this->createSuccessResponse(
            static::TYPE,
            static::TYPE,
        );
    }
}
