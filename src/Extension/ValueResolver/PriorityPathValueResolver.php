<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\ConfigurableAbstractValueResolver;
use SprykerSdk\Sdk\Extension\Exception\CanNotResolveValueException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class PriorityPathValueResolver extends ConfigurableAbstractValueResolver
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'PRIORITY_PATH';
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @throws \SprykerSdk\Sdk\Extension\Exception\CanNotResolveValueException
     *
     * @return string
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false): string
    {
        $relativePath = (string)parent::getValue($context, $settingValues, $optional);

        foreach ($this->getSettingPaths() as $settingKey) {
            $path = $settingValues[$settingKey];
            if (strpos($path, DIRECTORY_SEPARATOR, -1) === 0) {
                $path = rtrim($path, DIRECTORY_SEPARATOR);
            }
            $path = sprintf('%s/%s', $path, $relativePath);
            if (file_exists($path)) {
                return $this->formatValue($path);
            }
        }
        if (!$this->getSettingPaths()) {
            $path = sprintf('%s/%s', getcwd(), $relativePath);

            if (file_exists($path)) {
                return $this->formatValue($relativePath);
            }
        }

        throw new CanNotResolveValueException('Can\'t resolve path.');
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

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'path';
    }
}
