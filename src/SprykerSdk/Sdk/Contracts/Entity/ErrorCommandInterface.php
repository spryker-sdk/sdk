<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

use SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse;

interface ErrorCommandInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dto\CommandResponse $commandResponse
     *
     * @return string
     */
    public function getErrorMessage(CommandResponse $commandResponse): string;
}
