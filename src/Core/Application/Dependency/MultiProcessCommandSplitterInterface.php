<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use ArrayObject;

interface MultiProcessCommandSplitterInterface
{
    /**
     * @param mixed $value
     *
     * @return \ArrayObject
     */
    public function split($value = null): ArrayObject;

    /**
     * @return int
     */
    public function getConcurrentProcessNum(): int;
}
