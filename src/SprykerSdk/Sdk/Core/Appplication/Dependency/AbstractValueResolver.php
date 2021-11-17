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
     * @param array $settingValues
     *
     * @return mixed
     */
    public function getValue(array $settingValues): mixed
    {
        if ($this->valueReceiver->has($this->getValueName())) {
            return $this->valueReceiver->get($this->getValueName(), $this->getDescription());
        }

        $requiredSettings = array_intersect(array_keys($settingValues), $this->getRequiredSettingPaths());

        if (count($requiredSettings) !== count($this->getRequiredSettingPaths())) {
            throw new MissingSettingException(
                'Required settings are missing: ' . implode(', ', array_diff($this->getRequiredSettingPaths(), $settingValues))
            );
        }

        try {
            return $this->getValueFromSettings($settingValues);
        } catch (MissingValueException) {
            return $this->valueReceiver->get($this->getValueName(), $this->getDescription());
        }
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