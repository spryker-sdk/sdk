<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Setting
{
    /**
     * @param string $id
     * @param mixed $value
     * @param string $strategy
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Setting|null $parentSetting
     * @param bool $hasInitialization
     */
    public function __construct(
        public string $id,
        public mixed  $value,
        public string $strategy,
        public ?Setting $parentSetting = null,
        public bool   $hasInitialization = false,
    ) {
    }
}