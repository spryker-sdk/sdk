<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class ReportDirValueResolver extends OriginValueResolver
{
    /**
     * @var string
     */
    public const RESOLVER_ID = 'REPORT_DIR';

   /**
    * {@inheritDoc}
    *
    * @return string
    */
    public function getId(): string
    {
        return static::RESOLVER_ID;
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false)
    {
        $value = parent::getValue($context, $settingValues, $optional);

        $reportDir = $settingValues[Setting::PATH_REPORT_DIR];

        if (!is_dir($reportDir)) {
            mkdir($reportDir, 0777, true);
        }

        return $this->formatValue($reportDir . DIRECTORY_SEPARATOR . $value);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [Setting::PATH_REPORT_DIR];
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return ValueTypeEnum::TYPE_PATH;
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return $this->getSettingPaths();
    }

    /**
     * @param array $settingValues
     *
     * @return string|null
     */
    protected function getValueFromSettings(array $settingValues): ?string
    {
        return null;
    }
}
