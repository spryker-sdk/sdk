<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Exception;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException as SymfonyInvalidConfigurationException;
use Throwable;

class InvalidRequestDataException extends SymfonyInvalidConfigurationException
{
    /**
     * @param string $field
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $field, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Invalid request. Parameter %s is missing.', $field), $code, $previous);
    }
}
