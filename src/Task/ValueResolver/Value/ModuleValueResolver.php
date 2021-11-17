<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\ValueResolver\Value;

class ModuleValueResolver implements ValueResolverInterface
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'MODULE';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Module';
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function getValue(array $settings)
    {
        return null;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [];
    }

    /**
     * E.g.: string, bool, int, path
     *
     * @return string
     */
    public function getType(): string
    {
        return 'string';
    }
}
