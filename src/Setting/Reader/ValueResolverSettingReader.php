<?php

namespace Sdk\Setting\Reader;

use Sdk\Exception\PathNotFoundException;
use Sdk\Exception\SettingNotFoundException;
use Sdk\Setting\SettingInterface;
use Sdk\Task\ValueResolver\Value\CodeBucketValueResolver;
use Sdk\Task\ValueResolver\Value\EnvironmentValueResolver;
use Sdk\Task\ValueResolver\Value\ModuleDirValueResolver;
use Sdk\Task\ValueResolver\Value\ProjectDirValueResolver;
use Sdk\Task\ValueResolver\Value\SdkDirValueResolver;
use Sdk\Task\ValueResolver\Value\StoreValueResolver;
use Sdk\Task\ValueResolver\Value\ValueResolverInterface;
use Sdk\Task\ValueResolver\Value\VendorDirValueResolver;
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
     * @param string $rootDirPath
     * @param \Sdk\Setting\SettingInterface $setting
     */
    public function __construct(string $rootDirPath, SettingInterface $setting)
    {
        $this->rootDirPath = $rootDirPath;
        $this->setting = $setting;
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

            include_once $pathName;

            $className = $valueResolverFile->getBasename('.' . $valueResolverFile->getExtension());

            $fullClassName = $namespace.'\\'.$className;

            if (class_exists($fullClassName) &&
                in_array(ValueResolverInterface::class, class_implements($fullClassName), true)
            ) {
                $valueResolvers[] = new $fullClassName();
            }
        }
        //@TODO Needs to autoload them from config

        $base = [
            new ModuleDirValueResolver(),
            new ProjectDirValueResolver(),
            new SdkDirValueResolver(),
            new VendorDirValueResolver(),
            new CodeBucketValueResolver(),
            new StoreValueResolver(),
            new EnvironmentValueResolver(),
        ];

        return array_merge($base, $valueResolvers);
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

        return $paths;
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
