<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

class PathResolver
{
    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param string $sdkBasePath
     */
    public function __construct(string $sdkBasePath, Filesystem $filesystem)
    {
        $this->sdkBasePath = $sdkBasePath;
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getResolveRelativePath(string $path): string
    {
        if ($this->filesystem->isAbsolutePath($path)) {
            return $path;
        }

        $path = preg_replace('/^\P{L}+/u', '', $path);

        $path = $this->sdkBasePath . DIRECTORY_SEPARATOR . $path;

        return rtrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getResolveProjectRelativePath(string $path): string
    {
        if ($this->filesystem->isAbsolutePath($path)) {
            return $path;
        }

        $path = preg_replace('/^\P{L}+/u', '', $path);

        $path = $this->filesystem->getcwd() . DIRECTORY_SEPARATOR . $path;

        return rtrim($path, DIRECTORY_SEPARATOR);
    }
}
