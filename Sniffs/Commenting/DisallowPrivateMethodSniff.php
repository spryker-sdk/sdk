<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sniffs\Commenting;

use SprykerSdk\Sniffs\AbstractSniffs\AbstractDisallowPrivateSniff;

class DisallowPrivateMethodSniff extends AbstractDisallowPrivateSniff
{
    /**
     * @inheritDoc
     */
    public function register(): array
    {
        return [
            T_FUNCTION,
        ];
    }
}
