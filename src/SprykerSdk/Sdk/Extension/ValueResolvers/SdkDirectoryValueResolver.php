<?php

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\Sdk\Contracts\ValueResolver\AbstractValueResolver;

class SdkDirectoryValueResolver extends AbstractValueResolver
{
    const SETTING_SDK_DIR = 'sdk_dir';

    /**
     * @param \SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface $valueReceiver
     * @param string $sdkBasePath
     */
    public function __construct(
        ValueReceiverInterface $valueReceiver,
        protected string $sdkBasePath
    ) {
        parent::__construct($valueReceiver);
    }

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
        if (!isset($settingValues[self::SETTING_SDK_DIR])) {
            return $this->sdkBasePath;
        }

        $settingSdkBasePath = $settingValues[self::SETTING_SDK_DIR];

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
     * @return string[]
     */
    public function getSettingPaths(): array
    {
        return [
            self::SETTING_SDK_DIR,
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
