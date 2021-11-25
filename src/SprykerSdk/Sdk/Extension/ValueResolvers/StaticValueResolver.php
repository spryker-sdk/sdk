<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\Dependency\AbstractValueResolver;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ConfigurableValueResolverInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;

class StaticValueResolver extends AbstractValueResolver implements ConfigurableValueResolverInterface
{
    protected mixed $value;

    protected string $alias;

    protected mixed $description;

    protected array $settingPaths;

    /**
     * @var mixed|null
     */
    protected ?string $help;

    /**
     * @var mixed|string
     */
    protected string $type;

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'STATIC';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return $this->settingPaths;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param array<string, mixed> $values
     *
     * @return void
     */
    public function configure(array $values): void
    {
        $this->value = $values['defaultValue'] ?? null;
        $this->alias = $values['name'];
        $this->description = $values['description'];
        $this->help = $values['help'] ?? null;
        $this->type = $values['type'] ?? 'string';
        $this->settingPaths = $values['settingPaths'] ?? [];
    }

    /**
     * @return array<string>
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues): mixed
    {
        if (!isset($settingValues[$this->getAlias()])) {
            throw new MissingValueException();
        }

        return $settingValues[$this->getAlias()]->getValues();
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->value;
    }
}
