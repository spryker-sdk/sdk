<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\SdkContracts\Enum\Setting;

/**
 * @deprecated Use \SprykerSdk\Sdk\Extension\ValueResolverPriorityPathValueResolver instead.
 */
class ConfigPathValueResolver extends PriorityPathValueResolver
{
    /**
     * @var string
     */
    public const RESOLVER_ID = 'CONFIG_PATH';

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
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return $this->getRequiredSettingPaths();
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [Setting::PATH_PROJECT_DIR, Setting::PATH_SDK_DIR];
    }
}
