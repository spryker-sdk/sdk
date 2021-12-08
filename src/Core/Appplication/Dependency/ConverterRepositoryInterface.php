<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Contracts\Entity\CommandInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;

interface ConverterRepositoryInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\CommandInterface $command
     *
     * @return \SprykerSdk\Sdk\Core\Domain\Entity\Converter|null
     */
    public function getConverter(CommandInterface $command): ?Converter;
}
