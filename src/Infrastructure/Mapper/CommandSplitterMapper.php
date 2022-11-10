<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\CommandSplitter as DomainCommandSplitter;
use SprykerSdk\Sdk\Infrastructure\Entity\CommandSplitter as InfrastructureCommandSplitter;

class CommandSplitterMapper
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\CommandSplitter $domainCommandSplitter
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\CommandSplitter
     */
    public function mapDomainEntityToInfrastructure(
        DomainCommandSplitter $domainCommandSplitter
    ): InfrastructureCommandSplitter {
        return new InfrastructureCommandSplitter(
            $domainCommandSplitter->getClass(),
        );
    }
}
