<?php

namespace Sdk\Setting\Reader;

use Sdk\Setting\SettingInterface;

class ProjectDirReader implements SettingReaderInterface
{
    /**
     * Setting key from `config/settings/settings.yml`
     */
    protected const REPORT_DIR = 'project_dir';

    /**
     * @var \Sdk\Setting\SettingInterface
     */
    protected SettingInterface $setting;

    /**
     * @param \Sdk\Setting\SettingInterface $setting
     */
    public function __construct(SettingInterface $setting)
    {
        $this->setting = $setting;
    }

    public function read(): string
    {
        return $this->setting->getSetting(static::REPORT_DIR);
    }
}
