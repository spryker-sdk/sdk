<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dto\Abstraction;

interface FromArrayToArrayInterface
{
    /**
     * @param array $data
     * @param bool $ignoreMissing
     *
     * @return static
     */
    public static function fromArray(array $data, bool $ignoreMissing = false): static;

    /**
     * @return array
     */
    public function toArray(): array;
}
