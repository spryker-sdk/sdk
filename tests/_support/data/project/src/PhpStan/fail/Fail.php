<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks\PhpStan;

class Fail
{
    /**
     * @var array<string>
     */
    protected array $test;

    /**
     * @param string $test
     */
    public function __construct(string $test)
    {
        $this->test = $test;
    }
}
