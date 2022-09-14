<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class OptionValueResolver extends StaticValueResolver
{
    /**
     * @var bool
     */
    protected bool $hasDefaultValue = false;

    /**
     * @var string
     */
    protected string $commandParameter;

    /**
     * {@inheritDoc}
     *
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = true)
    {
        if ($optional && !$this->hasDefaultValue) {
            $optional = !$this->valueReceiver->receiveValue(
                new ReceiverValue(
                    sprintf('Would you like to configure `%s` setting? (%s)', $this->getValueName(), $this->getDescription()),
                    false,
                    'boolean',
                ),
            );
        }

        $value = parent::getValue($context, $settingValues, $optional);

        return $value ? sprintf('--%s=\'%s\'', $this->commandParameter ?? $this->getAlias(), $value) : null;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getId(): string
    {
        return 'OPTION';
    }

    /**
     * {@inheritDoc}
     *
     * @param array $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        $this->hasDefaultValue = array_key_exists('defaultValue', $values);

        if (isset($values['param'])) {
            $this->commandParameter = $values['param'];
        }

        parent::configure($values);
    }
}
