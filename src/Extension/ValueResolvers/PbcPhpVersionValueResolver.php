<?php

namespace SprykerSdk\Sdk\Extension\ValueResolvers;

use SprykerSdk\Sdk\Core\Appplication\ValueResolver\AbstractValueResolver;

class PbcPhpVersionValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const VALUE_NAME = 'pbc_php_version';

    /**
     * @var string
     */
    public const VALUE_RESOLVER_NAME = 'PBC_PHP_VERSION';

    /**
     * Information need to map to https://hub.docker.com/r/spryker/php
     *
     * @var array<string, string>
     */
    public const PHP_VERSIONS = [
        '7.4' => '7.4.28',
        '8.0' => '8.0.7',
    ];

    /**
     * @return array<string>
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
        return [];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return static::VALUE_RESOLVER_NAME;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'PHP version to use for the PBC';
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return static::VALUE_NAME;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue(): mixed
    {
        return array_key_first(static::PHP_VERSIONS);
    }

    /**
     * @param array $settingValues
     * @param array $resolvedValues
     *
     * @return array
     */
    public function getChoiceValues(array $settingValues, array $resolvedValues = []): array
    {
        return array_keys(static::PHP_VERSIONS);
    }
}
