<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;
use SprykerSdk\Sdk\Core\Domain\Enum\Setting;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;

class SdkDirectoryValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    protected string $sdkBasePath;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $valueReceiver
     * @param string $sdkBasePath
     */
    public function __construct(
        InteractionProcessorInterface $valueReceiver,
        string $sdkBasePath
    ) {
        $this->sdkBasePath = $sdkBasePath;
        parent::__construct($valueReceiver);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'SDK_DIR';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Relative to the installation directory of the SDK or absolute path';
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [
            Setting::PATH_SDK_DIR,
        ];
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
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return null;
    }

    /**
     * @param array $settingValues
     *
     * @return string
     */
    protected function getValueFromSettings(array $settingValues): string
    {
        if (!isset($settingValues[Setting::PATH_SDK_DIR])) {
            return $this->sdkBasePath;
        }

        $settingSdkBasePath = $settingValues[Setting::PATH_SDK_DIR];

        if (realpath($settingSdkBasePath) !== false) {
            return realpath($settingSdkBasePath);
        }

        return $this->sdkBasePath . DIRECTORY_SEPARATOR . $settingSdkBasePath;
    }
}
