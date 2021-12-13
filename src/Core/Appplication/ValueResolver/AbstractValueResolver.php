<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\ValueResolver;

use SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface;
use SprykerSdk\Sdk\Contracts\ValueResolver\ValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;

abstract class AbstractValueResolver implements ValueResolverInterface
{
    /**
     * @var \SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface
     */
    protected ValueReceiverInterface $valueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Contracts\ValueReceiver\ValueReceiverInterface $valueReceiver
     */
    public function __construct(ValueReceiverInterface $valueReceiver)
    {
        $this->valueReceiver = $valueReceiver;
    }

    /**
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     * @param bool|false $optional
     * @param array<string, mixed> $resolvedValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return mixed
     */
    public function getValue(array $settingValues, bool $optional = false, array $resolvedValues = []): mixed
    {
        if ($this->valueReceiver->has($this->getValueName())) {
            return $this->valueReceiver->get($this->getValueName());
        }

        $requiredSettings = array_intersect(array_keys($settingValues), $this->getRequiredSettingPaths());

        if (count($requiredSettings) !== count($this->getRequiredSettingPaths())) {
            throw new MissingSettingException(
                'Required settings are missing: ' . implode(', ', array_diff($this->getRequiredSettingPaths(), $settingValues)),
            );
        }
        $choiceValues = $this->getChoiceValues($settingValues, $resolvedValues);

        $defaultValue = $this->getDefaultValue();

        if ($defaultValue === null) {
            try {
                $defaultValue = $this->getValueFromSettings($settingValues);
            } catch (MissingValueException) {
                $defaultValue = null;
            }
        }

        if (!$optional) {
            $defaultValue = $this->valueReceiver->receiveValue(
                new ReceiverValue(
                    $this->getDescription(),
                    $defaultValue,
                    $this->getType(),
                    $choiceValues,
                ),
            );
        }

        return $defaultValue;
    }

    /**
     * @param array $settingValues
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $settingValues, array $resolvedValues = []): array
    {
        return [];
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

    /**
     * @return array<string>
     */
    abstract protected function getRequiredSettingPaths(): array;

    /**
     * @param array<string, mixed> $settingValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return mixed
     */
    abstract protected function getValueFromSettings(array $settingValues): mixed;
}
