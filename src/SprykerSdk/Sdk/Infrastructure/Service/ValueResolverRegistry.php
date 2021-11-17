<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Composer\Autoload\ClassLoader;
use SplFileInfo;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use Symfony\Component\Finder\Finder;

class ValueResolverRegistry implements ValueResolverRegistryInterface
{
    /**
     * @var array<string, \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface>
     */
    protected ?array $valueResolvers = null;

    /**
     * @var array<string, \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface>
     */
    protected ?array $valueResolversClasses = null;

    protected ClassLoader $classLoader;

    public function __construct(
        protected SettingRepositoryInterface $settingRepository,

        protected string $sdkBasePath
    ) {
        $this->classLoader = require $this->sdkBasePath . '/vendor/autoload.php';
    }

    public function has(string $id): bool
    {
        $this->loadValueResolvers();

        if ($this->hasId($id)) {
            return true;
        }

        return $this->hasClass($id);
    }

    /**
     * @param string $id
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverInterface|null
     */
    public function get(string $id): ?ValueResolverInterface
    {
        if ($this->hasId($id)) {
            return $this->valueResolvers[$id];
        }

        if ($this->hasClass($id)) {
            return $this->valueResolversClasses[$id];
        }

        return null;
    }

    protected function loadValueResolvers()
    {
        if ($this->valueResolvers !== null) {
            return;
        }

        $paths = $this->settingRepository->findOneByPath('value_resolver_dirs');
        $pathCandidates = array_merge($paths->values, array_map(function (string $path) {
            return preg_replace('|//|', '/', $this->sdkBasePath . '/' . $path);
        }, $paths->values));

        $pathCandidates = array_filter($pathCandidates, function (string $path) {
            return is_dir($path);
        });
        $valueResolverFiles = Finder::create()->in($pathCandidates)->name('*ValueResolver.php');

        $this->valueResolvers = [];
        $this->valueResolversClasses = [];

        foreach ($valueResolverFiles->files() as $valueResolverFile) {
            $pathName = $valueResolverFile->getPathname();
            $namespace = $this->retrieveNamespaceFromFile($pathName);
            if ($namespace === null) {
                continue;
            }

            $fullClassName = $this->autoloadValueResolver($valueResolverFile, $namespace);

            $valueResolver = new $fullClassName();

            if (!$valueResolver instanceof ValueResolverInterface) {
                throw new InvalidTypeException(sprintf('Value resolver (%s) must implement %s', $valueResolver::class, ValueResolverInterface::class));
            }

            $this->valueResolvers[$valueResolver->getId()] = $valueResolver;
            $this->valueResolversClasses[get_class($valueResolver)] = $valueResolver;
        }
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
     * @param string $pathName
     *
     * @return string|null
     */
    protected function retrieveNamespaceFromFile(string $pathName): ?string
    {
        $fileContent = file_get_contents($pathName);

        if (preg_match('#(namespace)(\\s+)([A-Za-z0-9\\\\]+?)(\\s*);#sm', $fileContent, $matches)) {
            return $matches[3];
        }

        return null;
    }

    /**
     * @param \SplFileInfo $valueResolverFile
     * @param string $namespace
     *
     * @return string
     */
    protected function autoloadValueResolver(SplFileInfo $valueResolverFile, string $namespace): string
    {
        $className = $valueResolverFile->getBasename('.' . $valueResolverFile->getExtension());

        $namespace .= '\\';
        $fullClassName = $namespace . $className;

        if (!$this->isClassOrInterfaceDeclared($fullClassName)) {
            $this->classLoader->addPsr4($namespace, $valueResolverFile->getPath());
            $this->classLoader->loadClass($fullClassName);
        }

        return $fullClassName;
    }

    /**
     * @param string $id
     * @return bool
     */
    protected function hasId(string $id): bool
    {
        return array_key_exists($id, $this->valueResolvers);
    }

    /**
     * @param string $id
     * @return bool
     */
    protected function hasClass(string $id): bool
    {
        return array_key_exists($id, $this->valueResolversClasses);
    }
}