<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

interface ValueReceiverInterface
{
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param string $description
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return mixed
     */
    public function get(string $key, string $description): mixed;
}