<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\Sdk\Contracts\ValueResolver\AbstractValueResolver;

class SdkDirectoryValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const SETTING_SDK_DIR = 'sdk_dir';

    protected string $sdkBasePath;

    /**
     * @param \SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface $valueReceiver
     * @param string $sdkBasePath
     */
    public function __construct(
        ValueReceiverInterface $valueReceiver,
        string $sdkBasePath
    ) {
        $this->sdkBasePath = $sdkBasePath;
        parent::__construct($valueReceiver);
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array $settingValues
     *
     * @return string
     */
    protected function getValueFromSettings(array $settingValues): string
    {
        if (!isset($settingValues[static::SETTING_SDK_DIR])) {
            return $this->sdkBasePath;
        }

        $settingSdkBasePath = $settingValues[static::SETTING_SDK_DIR];

        if (realpath($settingSdkBasePath) !== false) {
            return realpath($settingSdkBasePath);
        }

        return $this->sdkBasePath . '/' . $settingSdkBasePath;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'SDK_DIR';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Relative to the installation directory of the SDK or absolute path';
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [
            static::SETTING_SDK_DIR,
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'path';
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return 'sdk-dir';
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->sdkBasePath;
    }
}
