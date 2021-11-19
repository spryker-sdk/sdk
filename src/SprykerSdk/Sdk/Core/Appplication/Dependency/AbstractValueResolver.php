<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;

abstract class AbstractValueResolver implements ValueResolverInterface
{
    public function __construct(
        protected ValueReceiverInterface $valueReceiver
    ) {
    }

    /**
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     * @param bool|false $optional
     *
     * @return mixed
     */
    public function getValue(array $settingValues, bool $optional=false): mixed
    {
        if ($this->valueReceiver->hasOption($this->getValueName())) {
            return $this->valueReceiver->getOption($this->getValueName());
        }

        $requiredSettings = array_intersect(array_keys($settingValues), $this->getRequiredSettingPaths());

        if (count($requiredSettings) !== count($this->getRequiredSettingPaths())) {
            throw new MissingSettingException(
                'Required settings are missing: ' . implode(', ', array_diff($this->getRequiredSettingPaths(), $settingValues))
            );
        }
        $defaultValue = $this->getDefaultValue();

        if (!$defaultValue) {
            $defaultValue = $settingValues[$this->getAlias()] ? $settingValues[$this->getAlias()]->getValues() : null;
        }
        if (!$defaultValue) {
            try {
                $defaultValue = $this->getValueFromSettings($settingValues);
            } catch (MissingValueException) {}
        }
        if (!$defaultValue || !$optional) {
            return $this->valueReceiver->askValue($this->getDescription(), $defaultValue, $this->getType());
        }

        return $defaultValue;
    }

    /**
     * @return string
     */
    protected function getValueName(): string
    {
        if ($this->getAlias()) {
            return $this->getAlias();
        }

        return $this->getId();
    }

    protected abstract function getRequiredSettingPaths(): array;

    /**
     * @param array<string, mixed> $settingValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return mixed
     */
    protected abstract function getValueFromSettings(array $settingValues): mixed;
}
