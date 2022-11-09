<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\SettingInterface as ContractSettingInterface;

interface SettingInterface extends ContractSettingInterface
{
    /**
     * @return bool
     */
    public function isFirstInitialization(): bool;
}
