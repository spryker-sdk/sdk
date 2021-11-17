<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Setting
{
    /**
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     * @param bool $isProject
     * @param bool $hasInitialization
     * @param string|null $initializationDescription
     */
    public function __construct(
        public string   $path,
        public mixed    $values,
        public string   $strategy,
        public bool $isProject = true,
        public bool     $hasInitialization = false,
        public ?string $initializationDescription = null
    ) {
    }
}