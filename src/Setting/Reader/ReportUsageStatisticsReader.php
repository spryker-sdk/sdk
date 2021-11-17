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

    public function read(): bool
    {
        $setting = $this->setting->getSetting(static::REPORT_USAGE_STATISTICS);

        return $setting === null ? false : $setting;
    }
}
