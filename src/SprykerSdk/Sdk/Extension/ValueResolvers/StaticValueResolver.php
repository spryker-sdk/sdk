<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSettingPaths(): array
    {
        return $this->settingPaths;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

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
     * @return array
     */
    protected function getRequiredSettingPaths(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $settingValues
     *
     * @return mixed
     */
    protected function getValueFromSettings(array $settingValues): mixed
    {
        if ($this->value === null) {
            throw new MissingValueException();
        }

        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return $this->value;
    }
}
