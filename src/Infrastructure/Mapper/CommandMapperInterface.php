<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Infrastructure\Entity\Command;
use SprykerSdk\SdkContracts\Entity\CommandInterface;

interface CommandMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Command>
     */
    public function mapCommand(CommandInterface $command): Command;
}
