<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface;

class SdkDirectoryValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const SETTING_SDK_DIR = 'sdk_dir';

    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface $valueReceiver
     * @param string $sdkBasePath
     */
    public function __construct(
        ReceiverInterface $valueReceiver,
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
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        return null;
    }
}
