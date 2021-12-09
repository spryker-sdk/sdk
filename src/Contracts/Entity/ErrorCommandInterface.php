<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

interface ErrorCommandInterface extends CommandInterface
{
    /**
     * @return string
     */
    public function getErrorMessage(): string;
}
