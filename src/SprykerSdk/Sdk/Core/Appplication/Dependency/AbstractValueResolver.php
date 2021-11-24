<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use SprykerSdk\Sdk\Core\Appplication\Service\PathResolver;

abstract class AbstractValueResolver implements ValueResolverInterface
{
    public function __construct(
        protected ValueReceiverInterface $valueReceiver,
        protected PathResolver $pathResolver
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
        if ($this->valueReceiver->has($this->getValueName())) {
            return $this->valueReceiver->get($this->getValueName());
        }

        $requiredSettings = array_intersect(array_keys($settingValues), $this->getRequiredSettingPaths());

        if (count($requiredSettings) !== count($this->getRequiredSettingPaths())) {
            throw new MissingSettingException(
                'Required settings are missing: ' . implode(', ', array_diff($this->getRequiredSettingPaths(), $settingValues))
            );
        }
        $defaultValue = $this->getDefaultValue();

        if ($defaultValue === null) {
            try {
                $defaultValue = $this->getValueFromSettings($settingValues);
            } catch (MissingValueException) {
                $defaultValue = null;
            }
        }

        if (!$optional) {
            $defaultValue = $this->valueReceiver->receiveValue($this->getDescription(), $defaultValue, $this->getType());
            if ($this->getType() == 'path') {
                $defaultValue = $this->pathResolver->getResolveRelativePath($defaultValue);
            }
        }

        return $defaultValue;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getResolveRelativePath($path): string
    {
        if (strpos($path, DIRECTORY_SEPARATOR) === 0)
        {
            return $path;
        }
        $path = preg_replace('~^\P{L}+~u', '', $path);

        $path = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . $path;

        return rtrim($path, DIRECTORY_SEPARATOR);
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
