<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\Exception\InvalidSettingException;
use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\ValueResolver\ConfigurableValueResolverInterface;

class OptionValueResolver extends AbstractValueResolver implements ConfigurableValueResolverInterface
{
    /**
     * @var string
     */
    protected const OPTION_TYPE = 'option';

    /**
     * @var string
     */
    protected const ARGUMENT_TYPE = 'argument';

    /**
     * @var string
     */
    protected string $type = '';

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
        $value = parent::getValue($context, $settingValues, true);

        if (!$value) {
            return null;
        }

        if ($this->type === static::OPTION_TYPE) {
            return sprintf('--%s=%s', $this->name, $value);
        }

        if ($this->type === static::ARGUMENT_TYPE) {
            return sprintf('%s', $value);
        }

        return null;
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
     * @return string
     */
    public function getDefaultValue(): string
    {
        return '';
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
     * @return array
     */
    protected function getValueFromSettings(array $settingValues): array
    {
        return [];
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

        $type = $values['type'] ?? '';
        $this->validateType($type);

        $this->type = $type;
    }

    /**
     * @param string $type
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\InvalidSettingException
     *
     * @return void
     */
    protected function validateType(string $type): void
    {
        $types = [static::ARGUMENT_TYPE, static::OPTION_TYPE];

        if (!$type || !in_array($type, $types)) {
            throw new InvalidSettingException(
                sprintf(
                    'Setting "%s" is invalid. Available values: %s',
                    $type,
                    implode(',', $types),
                ),
            );
        }
    }
}
