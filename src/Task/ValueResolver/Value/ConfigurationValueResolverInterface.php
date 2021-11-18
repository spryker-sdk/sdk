<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\ValueResolver\Value;

interface ConfigurationValueResolverInterface extends ValueResolverInterface
{
    /**
     * @param array<string, mixed> $configuration
     *
     * @return string|null
     */
    public function getParameterName(array $configuration = []): ?string;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param array $configuration
     *
     * @return string
     */
    public function getDescription(array $configuration = []): string;

    /**
     * @param array $configuration
     *
     * @param array $settings
     *
     * @return mixed
     */
    public function getValue(array $settings, array $configuration = []);

    /**
     * @param array $configuration
     *
     * @return array<string>
     */
    public function getSettingPaths(array $configuration = []): array;

    /**
     * E.g.: string, bool, int, path
     *
     * @param array $configuration
     *
     * @return string
     */
    public function getType(array $configuration = []): string;
}
