<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\ValueResolver\Value;

class FlagValueResolver implements ConfigurationValueResolverInterface
{
    /**
     * @param array<string, mixed> $configuration
     *
     * @return string|null
     */
    public function getParameterName(array $configuration = []): ?string
    {
        return $configuration['parameterName'] ?? null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'FLAG';
    }

    /**
     * @param array<string, mixed> $configuration
     *
     * @return string
     */
    public function getDescription(array $configuration = []): string
    {
        return $configuration['description'];
    }

    /**
     * @param array<string, mixed> $configuration
     *
     * @param array $settings
     *
     * @return mixed
     */
    public function getValue(array $settings, array $configuration = [])
    {
        return $configuration['defaultValue'];
    }

    /**
     * @param array<string, mixed> $configuration
     *
     * @return array<string>
     */
    public function getSettingPaths(array $configuration = []): array
    {
        return $configuration['settingPaths'];
    }

    /**
     * E.g.: string, bool, int, path
     *
     * @param array<string, mixed> $configuration
     *
     * @return string
     */
    public function getType(array $configuration = []): string
    {
        return $configuration['type'];
    }
}
