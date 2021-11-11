<?php

namespace Sdk\Setting\Reader;

use Sdk\Exception\PathNotFoundException;
use Sdk\Setting\SettingInterface;

class TaskSettingReader implements SettingReaderInterface
{
    /**
     * Setting key from `config/settings/settings.yml`
     */
    protected const TASK_DIRS = 'task_dirs';

    /**
     * @var string
     */
    protected $rootDirPath;

    /**
     * @var \Sdk\Setting\SettingInterface
     */
    protected $setting;

    /**
     * @param string $rootDirPath
     * @param \Sdk\Setting\SettingInterface $setting
     */
    public function __construct(string $rootDirPath, SettingInterface $setting)
    {
        $this->setting = $setting;
        $this->rootDirPath = $rootDirPath;
    }

    /**
     * @return mixed|array
     */
    public function read(): array
    {
        $paths = $this->setting->getSetting(static::TASK_DIRS);
        foreach ($paths as &$path) {
            if (strpos($path, '/') === false)
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
