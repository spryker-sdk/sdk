<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Extension\Exception\UnresolvableValueExceptionException;
use SprykerSdk\Sdk\Infrastructure\Exception\InvalidConfigurationException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class PriorityPathValueResolver extends OriginValueResolver
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'PRIORITY_PATH';
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @throws \SprykerSdk\Sdk\Extension\Exception\UnresolvableValueExceptionException
     * @throws \SprykerSdk\Sdk\Infrastructure\Exception\InvalidConfigurationException
     *
     * @return string
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): string
    {
        $relativePath = (string)parent::getValue($context, $settingValues, $optional);

        if (!$this->getSettingPaths()) {
            throw new InvalidConfigurationException(sprintf('`%s` resolver doesn\'t have any paths', $this->getId()));
        }

        foreach ($this->getSettingPaths() as $settingKey) {
            if (!isset($settingValues[$settingKey])) {
                continue;
            }
            $path = $settingValues[$settingKey];
            if ($path && strpos($path, DIRECTORY_SEPARATOR, -1) === 0) {
                $path = rtrim($path, DIRECTORY_SEPARATOR);
            }
            $path = implode(DIRECTORY_SEPARATOR, [$path, $relativePath]);
            if (file_exists($path)) {
                return $this->formatValue($path);
            }
        }

        throw new UnresolvableValueExceptionException('Can\'t resolve path.');
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
