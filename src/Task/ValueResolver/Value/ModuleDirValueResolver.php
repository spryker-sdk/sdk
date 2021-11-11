<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\ValueResolver\Value;

class ModuleDirValueResolver implements ValueResolverInterface
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return 'MODULE_DIR';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Base path to the module directory';
    }

    /**
     * @param array $parameters
     *
     * @return mixed
     */
    public function getValue(array $parameters)
    {
        return '';
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [
            'module_dir',
        ];
    }

    /**
     * E.g.: string, bool, int, path
     *
     * @return string
     */
    public function getType(): string
    {
        return 'path';
    }
}
