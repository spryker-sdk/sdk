<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\Sdk\Core\Application\Dto\PluggableTaskAction\AfterTaskCreationDto;

interface AfterTaskCreatedActionInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\PluggableTaskAction\AfterTaskCreationDto $afterTaskCreationDto
     *
     * @return void
     */
    public function execute(AfterTaskCreationDto $afterTaskCreationDto): void;
}
