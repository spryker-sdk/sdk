<?php

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class FlagValueResolver extends StaticValueResolver
{
    /**
     * @var string
     */
    protected string $flag;

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'FLAG';
    }

    public function configure(array $values): void
    {
        parent::configure($values);

        $this->flag = $values['flag'] ?? $this->alias;

    }

    /**
     * @param array<string, \SprykerSdk\Sdk\Infrastructure\Entity\Setting> $settingValues
     * @param bool|false $optional
     * @param array<string, mixed> $resolvedValues
     *
     * @return mixed
     */
    public function getValue(array $settingValues, bool $optional = false, array $resolvedValues = []): string
    {
        $defaultValue = parent::getValue($settingValues, $optional);

        return !$defaultValue ? '' : sprintf('--%s', $this->flag);
    }
}
