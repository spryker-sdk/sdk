<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Logger;

use Psr\Log\LoggerInterface;

interface ErrorLoggerFactoryInterface
{
    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getErrorLogger(): LoggerInterface;
}
