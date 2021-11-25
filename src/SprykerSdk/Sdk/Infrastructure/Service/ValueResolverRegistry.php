<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Composer\Autoload\ClassLoader;
use JetBrains\PhpStorm\Pure;
use SplFileInfo;
use SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ValueResolverRegistryInterface;
use SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use Symfony\Component\Finder\Finder;

class ValueResolverRegistry implements ValueResolverRegistryInterface
{
    /**
     * @var array<string, \SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface>
     */
    protected ?array $valueResolvers = null;

    /**
     * @var array<string, \SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface>
     */
    protected ?array $valueResolversClasses = null;

    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected ClassLoader $classLoader;

    /**
     * @param \SprykerSdk\Sdk\Contracts\Repository\SettingRepositoryInterface $settingRepository
     * @param \SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface $valueReceiver
     * @param iterable<\SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface> $valueResolverServices
     * @param string $sdkBasePath
     */
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected ValueReceiverInterface $valueReceiver,
        protected iterable $valueResolverServices,
        protected string $sdkBasePath
    ) {
        $this->classLoader = require $this->sdkBasePath . '/vendor/autoload.php';
    }

    /**
     * @param string $id
     *
     * @return bool
     */
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
     *
     * @return \SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface|null
     */
    #[Pure] public function get(string $id): ?ValueResolverInterface
    {
        if ($this->hasId($id)) {
            return $this->valueResolvers[$id];
        }

        if ($this->hasClass($id)) {
            return $this->valueResolversClasses[$id];
        }

        return null;
    }

    /**
     * @return void
     */
    protected function loadValueResolvers()
    {
        if ($this->valueResolvers !== null) {
            return;
        }

        $this->valueResolvers = [];
        $this->valueResolversClasses = [];

        $this->loadValueResolverServices();
        $this->loadValueResolversFromFiles();
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

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getValueResolverFiles(): Finder
    {
        $paths = $this->settingRepository->findOneByPath('value_resolver_dirs');
        $pathCandidates = array_merge($paths->getValues(), array_map(function (string $path) {
            return preg_replace('|//|', '/', $this->sdkBasePath . '/' . $path);
        }, $paths->getValues()));

        $pathCandidates = array_filter($pathCandidates, function (string $path) {
            return is_dir($path);
        });
        return Finder::create()->in($pathCandidates)->name('*ValueResolver.php');
    }

    /**
     * @return void
     */
    protected function loadValueResolverServices(): void
    {
        foreach ($this->valueResolverServices as $valueResolverService) {
            $this->valueResolvers[$valueResolverService->getId()] = $valueResolverService;
            $this->valueResolversClasses[get_class($valueResolverService)] = $valueResolverService;
        }
    }

    /**
     * @return void
     */
    protected function loadValueResolversFromFiles(): void
    {
        $valueResolverFiles = $this->getValueResolverFiles();

        foreach ($valueResolverFiles->files() as $valueResolverFile) {
            $pathName = $valueResolverFile->getPathname();
            $namespace = $this->retrieveNamespaceFromFile($pathName);
            if ($namespace === null) {
                continue;
            }

            $fullClassName = $this->autoloadValueResolver($valueResolverFile, $namespace);

            if (array_key_exists($fullClassName, $this->valueResolversClasses)) {
                continue;
            }
            $valueResolver = new $fullClassName($this->valueReceiver);

            if (!$valueResolver instanceof ValueResolverInterface) {
                throw new InvalidTypeException(sprintf('Value resolver (%s) must implement %s', $valueResolver::class, ValueResolverInterface::class));
            }

            $this->valueResolvers[$valueResolver->getId()] = $valueResolver;
            $this->valueResolversClasses[get_class($valueResolver)] = $valueResolver;
        }
    }
}
