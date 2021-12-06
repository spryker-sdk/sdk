<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Composer\Autoload\ClassLoader;
use SplFileInfo;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidFileException;
use Symfony\Component\Finder\Finder;

class AutoloaderService
{
    protected ClassLoader $classLoader;

    protected string $baseDirectory;

    /**
     * @param string $baseDirectory
     */
    public function __construct(string $baseDirectory)
    {
        $this->classLoader = require $baseDirectory . '/vendor/autoload.php';
        $this->baseDirectory = $baseDirectory;
    }

    /**
     * @param array<string> $directories
     * @param string $filePattern
     * @param callable|null $loadCallback
     *
     * @return void
     */
    public function loadClassesFromDirectory(
        array $directories,
        string $filePattern,
        ?callable $loadCallback = null
    ): void {
        if (!$loadCallback) {
            $loadCallback = function (string $loadableClassName): void {
            };
        }

        foreach ($this->getFiles($directories, $filePattern)->files() as $file) {
            $pathName = $file->getPathname();

            $namespace = $this->retrieveNamespaceFromFile($pathName);

            if ($namespace === null) {
                continue;
            }

            $fullClassName = $this->autoloadClass($file, $namespace);
            call_user_func($loadCallback, $fullClassName);
        }
    }

    /**
     * @param \SplFileInfo $fileInfo
     * @param string $namespace
     *
     * @return string
     */
    protected function autoloadClass(SplFileInfo $fileInfo, string $namespace): string
    {
        $className = $fileInfo->getBasename('.' . $fileInfo->getExtension());

        $namespace .= '\\';
        $fullClassName = $namespace . $className;

        if (!$this->isClassOrInterfaceDeclared($fullClassName)) {
            $this->classLoader->addPsr4($namespace, $fileInfo->getPath());
            $this->classLoader->loadClass($fullClassName);
        }

        return $fullClassName;
    }

    /**
     * @param string $signature
     *
     * @return bool
     */
    protected function isClassOrInterfaceDeclared(string $signature): bool
    {
        $signatures = array_merge(get_declared_interfaces(), get_declared_classes());

        return in_array($signature, $signatures, true);
    }

    /**
     * @param array $lookupDirectories
     * @param string $filePattern
     *
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFiles(array $lookupDirectories, string $filePattern): Finder
    {
        /** @var array<string> $pathCandidates */
        $pathCandidates = array_map(function (string $path) {
            return preg_replace('|//|', '/', $path);
        }, $lookupDirectories);

        return Finder::create()->in($pathCandidates)->name($filePattern);
    }

    /**
     * @param string $pathName
     *
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidFileException
     *
     * @return string|null
     */
    protected function retrieveNamespaceFromFile(string $pathName): ?string
    {
        $fileContent = file_get_contents($pathName);

        if (!$fileContent) {
            throw new InvalidFileException(sprintf('File %s has no content', $pathName));
        }

        if (preg_match('#(namespace)(\\s+)([A-Za-z0-9\\\\]+?)(\\s*);#sm', $fileContent, $matches)) {
            return $matches[3];
        }

        return null;
    }
}
