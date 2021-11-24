<?php

namespace SprykerSdk\Sdk\Core\Appplication\Service;

class PathResolver
{
    /**
     * @param string $sdkBasePath
     */
    public function __construct(
        protected string $sdkBasePath
    ) {

    }
    /**
     * @param string $path
     *
     * @return string
     */
    public function getResolveSdkRelativePath($path): string
    {
        if (strpos($path, DIRECTORY_SEPARATOR) === 0)
        {
            return $path;
        }
        $path = preg_replace('~^\P{L}+~u', '', $path);

        $path = $this->sdkBasePath . DIRECTORY_SEPARATOR . $path;

        return rtrim($path, DIRECTORY_SEPARATOR);
    }
}
