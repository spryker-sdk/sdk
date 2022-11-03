<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\RestApi\Controller\v1;

use SprykerSdk\Sdk\Core\Application\Version\AppVersionFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController
{
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(): Response
    {
        return new JsonResponse(['version' => $this->appVersionFetcher->fetchAppVersion()]);
    }
}
