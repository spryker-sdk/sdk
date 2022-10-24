<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Initializer\Plugin;

use SprykerSdk\Sdk\Core\Application\Dto\TaskInit\AfterTaskInitDto;

interface AfterTaskInitPluginInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskInit\AfterTaskInitDto $afterTaskInitDto
     *
     * @return void
     */
    public function execute(AfterTaskInitDto $afterTaskInitDto): void;
}
