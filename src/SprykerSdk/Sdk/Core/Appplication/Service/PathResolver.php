<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

class PathResolver
{
    protected string $sdkBasePath;

    /**
     * @param string $sdkBasePath
     */
    public function __construct(string $sdkBasePath)
    {
        $this->sdkBasePath = $sdkBasePath;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getResolveRelativePath(string $path): string
    {
        if (strpos($path, DIRECTORY_SEPARATOR) === 0) {
            return $path;
        }
        $path = preg_replace('/^\P{L}+/u', '', $path);

        $path = $this->sdkBasePath . DIRECTORY_SEPARATOR . $path;

        return rtrim($path, DIRECTORY_SEPARATOR);
    }
}
