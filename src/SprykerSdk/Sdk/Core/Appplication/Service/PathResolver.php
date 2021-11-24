<?php

namespace SprykerSdk\Sdk\Core\Appplication\Service;

class PathResolver
{
    /**
     * @param string $path
     *
     * @return string
     */
    public function getResolveRelativePath($path): string
    {
        if (strpos($path, DIRECTORY_SEPARATOR) === 0)
        {
            return $path;
        }
        $path = preg_replace('~^\P{L}+~u', '', $path);

        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $path;

        return rtrim($path, DIRECTORY_SEPARATOR);
    }
}
