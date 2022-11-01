<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Version;

use SprykerSdk\Sdk\Core\Application\Dependency\AppVersionFetcherInterface;

class AppVersionFetcher
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\AppVersionFetcherInterface
     */
    protected AppVersionFetcherInterface $appVersionFetcher;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\AppVersionFetcherInterface $appVersionFetcher
     */
    public function __construct(AppVersionFetcherInterface $appVersionFetcher)
    {
        $this->appVersionFetcher = $appVersionFetcher;
    }

    /**
     * @return string
     */
    public function fetchAppVersion(): string
    {
        return $this->appVersionFetcher->fetchAppVersion();
    }
}
