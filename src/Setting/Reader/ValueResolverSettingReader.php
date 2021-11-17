<?php

namespace Sdk\Setting\Reader;

use Composer\Autoload\ClassLoader;
use Sdk\Exception\PathNotFoundException;
use Sdk\Exception\SettingNotFoundException;
use Sdk\Setting\SettingInterface;
use Sdk\Task\ValueResolver\Value\ValueResolverInterface;
use Symfony\Component\Finder\Finder;

class ValueResolverSettingReader implements SettingReaderInterface
{
    /**
     * Setting key from `config/settings/settings.yml`
     */
    protected const VALUE_RESOLVER_DIRS = 'value_resolver_dirs';

    /**
     * @var string
     */
    protected string $rootDirPath;

    /**
     * @var \Sdk\Setting\SettingInterface
     */
    protected SettingInterface $setting;

    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected ClassLoader $classLoader;

    /**
     * @param string $rootDirPath
     * @param \Sdk\Setting\SettingInterface $setting
     */
    public function __construct(string $rootDirPath, SettingInterface $setting)
    {
        $this->rootDirPath = $rootDirPath;
        $this->setting = $setting;

        $this->classLoader = require $this->rootDirPath . 'vendor/autoload.php';
    }

    /**
     * @return mixed|array<string,\Sdk\Task\ValueResolver\Value\ValueResolverInterface>
     */
    public function read(): array
    {
        $pathDirs = $this->getValueResolverDirs();
        $finder = Finder::create();

        $valueResolverFiles = $finder
            ->in($pathDirs)
            ->files()
            ->name('*.php');

        $valueResolvers = [];

        foreach ($valueResolverFiles as $valueResolverFile) {
            $pathName = $valueResolverFile->getPathname();
            $namespace = $this->retrieveNamespaceFromFile($pathName);
            if ($namespace === null) {
                continue;
            }

            $className = $valueResolverFile->getBasename('.' . $valueResolverFile->getExtension());

            $namespace .= '\\';
            $fullClassName = $namespace.$className;

            if (!$this->isClassOrInterfaceDeclared($fullClassName)) {
                $this->classLoader->addPsr4($namespace, $valueResolverFile->getPath());
                $this->classLoader->loadClass($fullClassName);
            }

            if (class_exists($fullClassName) && in_array(ValueResolverInterface::class, class_implements($fullClassName), true)) {
                $valueResolvers[] = new $fullClassName();
            }
        }

        return $valueResolvers;
    }

    /**
     * @return mixed|array
     */
    public function getValueResolverDirs(): array
    {
        try {
            $paths = $this->setting->getSetting(static::VALUE_RESOLVER_DIRS);
        } catch (SettingNotFoundException $e) {
            return [];
        }

        foreach ($paths as &$path) {
            if (!strpos($path, '/'))
            {
                continue;
            }

            $path = $this->rootDirPath . $path;

            if (!file_exists( $path ) || !is_dir( $path )) {
                throw new PathNotFoundException(sprintf('Path `%s` is not found', $path));
            }
        }
        unset($path);

        return $paths;
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
}
