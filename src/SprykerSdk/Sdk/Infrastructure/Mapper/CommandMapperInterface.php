<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\Command;

interface CommandMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\Command>
     */
    public function mapCommand(CommandInterface $command): Command;
}
