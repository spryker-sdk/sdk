<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Placeholder
{
    /**
     * @param string $name
     * @param string $valueResolver
     * @param bool $isOptional
     */
    public function __construct(
        public string $name,
        public string $valueResolver,
        public bool $isOptional = false
    )
    {
    }
}