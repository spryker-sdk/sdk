<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface ActionApproverInterface
{
    /**
     * @param string $message
     *
     * @return bool
     */
    public function approve(string $message): bool;
}
