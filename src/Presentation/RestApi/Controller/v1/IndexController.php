<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Core\Application\Version\AppVersionFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends BaseController
{
    /**
     * @var string
     */
    public const TYPE = 'version';

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Version\AppVersionFetcher
     */
    protected AppVersionFetcher $appVersionFetcher;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Version\AppVersionFetcher $appVersionFetcher
     */
    public function __construct(AppVersionFetcher $appVersionFetcher)
    {
        $this->appVersionFetcher = $appVersionFetcher;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $version = $this->appVersionFetcher->fetchAppVersion();

        return $this->createSuccessResponse($version, static::TYPE, ['version' => $version]);
    }
}
