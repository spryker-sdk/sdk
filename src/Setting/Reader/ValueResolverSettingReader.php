<?php

namespace Sdk\Setting\Reader;

use Sdk\Exception\PathNotFoundException;
use Sdk\Exception\SettingNotFoundException;
use Sdk\Setting\SettingInterface;
use Sdk\Task\ValueResolver\Value\CodeBucketValueResolver;
use Sdk\Task\ValueResolver\Value\EnvironmentValueResolver;
use Sdk\Task\ValueResolver\Value\ModeValueResolver;
use Sdk\Task\ValueResolver\Value\ModuleDirValueResolver;
use Sdk\Task\ValueResolver\Value\ModuleNameValueResolver;
use Sdk\Task\ValueResolver\Value\ModuleValueResolver;
use Sdk\Task\ValueResolver\Value\OrganizationValueResolver;
use Sdk\Task\ValueResolver\Value\ProjectDirValueResolver;
use Sdk\Task\ValueResolver\Value\SdkDirValueResolver;
use Sdk\Task\ValueResolver\Value\StoreValueResolver;
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
    protected $rootDirPath;

    /**
     * @var \Sdk\Setting\SettingInterface
     */
    protected $setting = [];

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
        $loader = require $this->rootDirPath . 'vendor/autoload.php';

        $pathDirs = $this->getValueResolverDirs();
        $finder = Finder::create();

        $valueResolverFiles = $finder->in($pathDirs)->files();
        foreach ($valueResolverFiles as $valueResolverFile) {
             $name = $valueResolverFile->getBasename('.' . $valueResolverFile->getExtension());
        }
        //@TODO Needs to autoload them from config
        return [
            new ModuleDirValueResolver(),
            new ProjectDirValueResolver(),
            new SdkDirValueResolver(),
            new VendorDirValueResolver(),
            new CodeBucketValueResolver(),
            new StoreValueResolver(),
            new EnvironmentValueResolver(),
            new ModeValueResolver(),
            new ModuleValueResolver(),
            new OrganizationValueResolver()
        ];
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
}
