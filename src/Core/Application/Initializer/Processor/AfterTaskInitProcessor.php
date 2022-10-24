<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Initializer\Processor;

use SprykerSdk\Sdk\Core\Application\Dto\TaskInit\AfterTaskInitDto;

/**
 * @internal
 */
class AfterTaskInitProcessor
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Core\Application\Initializer\Plugin\AfterTaskInitPluginInterface>
     */
    protected iterable $actionPlugins;

    /**
     * @param iterable|\SprykerSdk\Sdk\Core\Application\Initializer\Plugin\AfterTaskInitPluginInterface[] $actionPlugins
     */
    public function __construct(iterable $actionPlugins)
    {
        $this->actionPlugins = $actionPlugins;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskInit\AfterTaskInitDto $afterTaskInitDto
     *
     * @return void
     */
    public function processAfterTaskInitialization(AfterTaskInitDto $afterTaskInitDto): void
    {
        foreach ($this->actionPlugins as $actionPlugin) {
            $actionPlugin->execute($afterTaskInitDto);
        }
    }
}
