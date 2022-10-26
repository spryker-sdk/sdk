<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface;
use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Application\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Application\Exception\MissingValueException;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueResolver\ValueResolverInterface;

abstract class AbstractValueResolver implements ValueResolverInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface
     */
    protected InteractionProcessorInterface $valueReceiver;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\InteractionProcessorInterface $valueReceiver
     */
    public function __construct(InteractionProcessorInterface $valueReceiver)
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
        if ($this->getAlias() && $this->valueReceiver->has($this->getAlias())) {
            return $this->valueReceiver->get($this->getAlias());
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
                    $this->getAlias(),
                ),
            );
        }

        return $defaultValue ?: null;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return null;
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
     * {@inheritDoc}
     *
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array<string, \SprykerSdk\SdkContracts\Entity\SettingInterface> $settingValues
     *
     * @return mixed|null
     */
    protected function getValueFromSettings(array $settingValues)
    {
        $settingPaths = $this->getSettingPaths();
        if (!$settingPaths) {
            return null;
        }

        $settingName = reset($settingPaths);

        return $settingValues[$settingName] ?? null;
    }
}
