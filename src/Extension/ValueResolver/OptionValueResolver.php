<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue;
use SprykerSdk\Sdk\Core\Domain\Enum\ValueTypeEnum;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * @deprecated Use `STATIC` value resolver with option configuration instead.
 */
class OptionValueResolver extends StaticValueResolver
{
    /**
     * @var bool
     */
    protected bool $hasDefaultValue = false;

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
        if ($optional && !$this->hasDefaultValue && $this->getAlias()) {
            $optional = !$this->valueReceiver->receiveValue(
                new ReceiverValue(
                    sprintf('Would you like to configure `%s` setting? (%s)', $this->getAlias(), $this->getDescription()),
                    false,
                    ValueTypeEnum::TYPE_BOOLEAN,
                ),
            );
        }

        $value = parent::getValue($context, $settingValues, $optional);

        return $value ? sprintf('--%s=%s', $this->getAlias(), $value) : null;
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

        parent::configure($values);
    }
}
