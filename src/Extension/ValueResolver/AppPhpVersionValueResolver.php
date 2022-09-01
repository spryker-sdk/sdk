<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\ValueResolver;

use SprykerSdk\Sdk\Core\Application\ValueResolver\AbstractValueResolver;

class AppPhpVersionValueResolver extends AbstractValueResolver
{
    /**
     * @var string
     */
    public const VALUE_NAME = 'app_php_version';

    /**
     * @var string
     */
    public const VALUE_RESOLVER_NAME = 'APP_PHP_VERSION';

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
        return 'PHP version to use for the App';
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
    public function getDefaultValue()
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
