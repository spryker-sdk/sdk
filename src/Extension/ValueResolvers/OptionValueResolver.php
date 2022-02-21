<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;

class OptionValueResolver extends AbstractValueResolver implements ConfigurableValueResolverInterface
{
    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     * @param array $settingValues
     * @param bool $optional
     *
     * @return mixed
     */
    public function getValue(ContextInterface $context, array $settingValues, bool $optional = true): mixed
    {
        $value = parent::getValue($context, $settingValues, $optional);

        if ($value === null) {
            return null;
        }

        return sprintf('--%s=%s', $this->name, $value);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'OPTION';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getSettingPaths(): array
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return null;
    }

    /**
     * @return array
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array $settingValues
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues): mixed
    {
        if (!isset($settingValues[$this->getAlias()])) {
            return null;
        }

        return $settingValues[$this->getAlias()];
    }

    /**
     * @param array $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        $this->name = $values['name'];
        $this->description = $values['description'] ?? '';
    }
}
