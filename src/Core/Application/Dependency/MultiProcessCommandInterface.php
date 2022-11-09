<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Entity\CommandInterface;

/**
 * @todo :: It must be moved to the sdk-contracts
 */
interface MultiProcessCommandInterface extends CommandInterface
{
    /**
     * @return callable
     */
    public function getSplitCallback(): callable;

    /**
     * @return int
     */
    public function getProcessesNum(): int;
}
