<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class OptionValueResolver extends StaticValueResolver
{
    /**
     * @var bool
     */
    protected bool $hasDefaultValue = false;

    /**
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

        return $value ? sprintf('--%s=\'%s\'', $this->getAlias(), $value) : null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'OPTION';
    }

    /**
     * @param array $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        $this->hasDefaultValue = array_key_exists('defaultValue', $values);

        parent::configure($values);
    }
}
