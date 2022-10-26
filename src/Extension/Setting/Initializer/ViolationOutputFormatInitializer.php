<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Setting\Initializer;

use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationReportFormatter;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\YamlViolationReportFormatter;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Setting\SettingChoicesProviderInterface;

class ViolationOutputFormatInitializer implements SettingChoicesProviderInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $setting
     *
     * @return array<string>
     */
    public function getChoices(SettingInterface $setting): array
    {
        return [OutputViolationReportFormatter::FORMAT, YamlViolationReportFormatter::FORMAT];
    }
}
