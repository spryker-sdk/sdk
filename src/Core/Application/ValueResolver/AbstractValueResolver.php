<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Exception\MissingValueException;
use SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface;

abstract class AbstractValueResolver implements ValueResolverInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface
     */
    protected ReceiverInterface $valueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Service\ValueReceiver\ReceiverInterface $valueReceiver
     */
    public function __construct(ReceiverInterface $valueReceiver)
    {
        $this->valueReceiver = $valueReceiver;
    }

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = false)
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
        $choiceValues = $this->getChoiceValues($settingValues, $context->getResolvedValues());

        $defaultValue = $this->getDefaultValue();

        if ($defaultValue === null) {
            try {
                $defaultValue = $this->getValueFromSettings($settingValues);
            } catch (MissingValueException $exception) {
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

        return $defaultValue ?: null;
    }

    /**
     * {@inheritDoc}
     *
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
     * @throws \SprykerSdk\Sdk\Core\Application\Exception\MissingValueException
     *
     * @return mixed
     */
    abstract protected function getValueFromSettings(array $settingValues);
}
