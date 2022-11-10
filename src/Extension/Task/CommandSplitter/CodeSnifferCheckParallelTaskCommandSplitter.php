<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Task\CommandSplitter;

use ArrayObject;
use SprykerSdk\Sdk\Core\Application\Dependency\MultiProcessCommandSplitterInterface;

class CodeSnifferCheckParallelTaskCommandSplitter implements MultiProcessCommandSplitterInterface
{
    /**
     * @param $value
     *
     * @return \ArrayObject
     */
    public function split($value = null): ArrayObject
    {
        $appComponents = [
            'src/Pyz/Glue',
            'src/Pyz/Client',
            'src/Pyz/Shared',
            'src/Pyz/Service',
            'src/Pyz/Zed',
            'config',
        ];

        $result = new ArrayObject();

        foreach ($appComponents as $appComponent) {
            $componentModules = array_diff((array)scandir($appComponent), ['..', '.']);
            if (!$componentModules) {
                continue;
            }
            foreach ($componentModules as $moduleDir) {
                $path = sprintf('%s/%s', $appComponent, $moduleDir);
                $result->append($path);
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getConcurrentProcessNum(): int
    {
        return 3;
    }
}
