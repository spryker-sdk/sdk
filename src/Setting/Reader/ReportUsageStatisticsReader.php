<?php

namespace Sdk\Setting\Reader;

use Sdk\Setting\SettingInterface;

class ReportUsageStatisticsReader implements SettingReaderInterface
{
    /**
     * Setting key from `config/settings/settings.yml`
     */
    protected const REPORT_USAGE_STATISTICS = 'report_usage_statistics';

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

    public function read(): bool
    {
        return $this->setting->getSetting(static::REPORT_USAGE_STATISTICS);
    }
}
