<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Extension\ValueResolver\Enum\Type;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ReportDirValueResolver extends OriginValueResolver
{
    /**
     * @var string
     */
    protected const REPORT_DIR_SETTING = 'report_dir';

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'REPORT_DIR';
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

        $reportDir = $settingValues[static::REPORT_DIR_SETTING];

        return $this->formatValue($reportDir . DIRECTORY_SEPARATOR . $value);
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [static::REPORT_DIR_SETTING];
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return Type::PATH_TYPE;
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
