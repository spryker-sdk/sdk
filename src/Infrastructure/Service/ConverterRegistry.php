<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Composer\Autoload\ClassLoader;
use ReflectionClass;
use SplFileInfo;
use SprykerSdk\Sdk\Core\Application\Dependency\ConverterRegistryInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidConverterException;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Report\ReportConverterInterface;
use Symfony\Component\Finder\Finder;

class ConverterRegistry implements ConverterRegistryInterface
{
    /**
     * @var bool
     */
    protected bool $isInitialized = false;

    /**
     * @var array<string, \SprykerSdk\SdkContracts\Report\ReportConverterInterface>
     */
    protected array $converterClasses = [];

    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected ClassLoader $classLoader;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param string $sdkBasePath
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface $settingRepository
     * @param string $sdkBasePath
     */
    public function __construct(
        SettingRepositoryInterface $settingRepository,
        string $sdkBasePath
    ) {
        $this->sdkBasePath = $sdkBasePath;
        $this->settingRepository = $settingRepository;
        $this->classLoader = require $this->sdkBasePath . '/vendor/autoload.php';
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function has(string $class): bool
    {
        $this->loadConverters();

        return $this->hasClass($class);
    }

    /**
     * @param string $class
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportConverterInterface|null
     */
    public function get(string $class): ?ReportConverterInterface
    {
        $this->loadConverters();

        if ($this->hasClass($class)) {
            return $this->converterClasses[$class];
        }

        return null;
    }

    /**
     * @return void
     */
    protected function loadConverters()
    {
        if ($this->isInitialized) {
            return;
        }

        $this->isInitialized = true;

        $this->loadConvertersFromFiles();
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
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidConverterException
     *
     * @return string|null
     */
    protected function retrieveNamespaceFromFile(string $pathName): ?string
    {
        $fileContent = file_get_contents($pathName);

        if (!$fileContent) {
            throw new InvalidConverterException('Could not read value converter from ' . $pathName);
        }

        if (preg_match('#(namespace)(\\s+)([A-Za-z0-9\\\\]+?)(\\s*);#sm', $fileContent, $matches)) {
            return $matches[3];
        }

        return null;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    protected function hasClass(string $class): bool
    {
        return array_key_exists($class, $this->converterClasses);
    }

    /**
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getConverterFiles(): Finder
    {
        $paths = $this->getConvertorDirectories();

        $pathCandidates = array_merge($paths, array_map(function (string $path): string {
            return (string)preg_replace('|//|', '/', $this->sdkBasePath . '/' . $path);
        }, $paths));

        $pathCandidates = array_filter($pathCandidates, function (string $path): bool {
            return is_dir($path);
        });

        return Finder::create()->in($pathCandidates)->name('*Converter.php');
    }

    /**
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return array<string>
     */
    protected function getConvertorDirectories(): array
    {
        $paths = $this->settingRepository->findOneByPath(Setting::PATH_EXTENSION_DIRS);

        if (!$paths) {
            throw new MissingSettingException(sprintf('Setting %s is missing', Setting::PATH_EXTENSION_DIRS));
        }

        $convertorDirs = [];
        foreach ($paths->getValues() as $candidateDir) {
            $dir = glob($candidateDir . '/Converter');

            if ($dir) {
                $convertorDirs[] = $dir;
            }
        }

        return array_merge(...$convertorDirs);
    }

    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidTypeException
     *
     * @return void
     */
    protected function loadConvertersFromFiles(): void
    {
        $converterFiles = $this->getConverterFiles();

        foreach ($converterFiles->files() as $converterFile) {
            $pathName = $converterFile->getPathname();
            $namespace = $this->retrieveNamespaceFromFile($pathName);
            if ($namespace === null) {
                continue;
            }

            $fullClassName = $this->autoloadConverter($converterFile, $namespace);

            if (array_key_exists($fullClassName, $this->converterClasses)) {
                continue;
            }
            $converterFile = new $fullClassName($this->settingRepository);

            if (!$converterFile instanceof ReportConverterInterface) {
                throw new InvalidTypeException(sprintf('Converter (%s) must implement %s', get_class($converterFile), ReportConverterInterface::class));
            }

            $this->converterClasses[(new ReflectionClass($converterFile))->getShortName()] = $converterFile;
        }
    }

    /**
     * @param \SplFileInfo $converterFile
     * @param string $namespace
     *
     * @return string
     */
    protected function autoloadConverter(SplFileInfo $converterFile, string $namespace): string
    {
        $className = $converterFile->getBasename('.' . $converterFile->getExtension());

        $namespace .= '\\';
        $fullClassName = $namespace . $className;

        if (!$this->isClassOrInterfaceDeclared($fullClassName)) {
            $this->classLoader->addPsr4($namespace, $converterFile->getPath());
            $this->classLoader->loadClass($fullClassName);
        }

        return $fullClassName;
    }
}
