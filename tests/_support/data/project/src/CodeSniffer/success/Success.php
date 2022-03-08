<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Acceptance\Extension\Tasks\CodeSniffer;

class Success
{
    /**
     * @var array<string>
     */
    protected array $test;

    /**
     * @param array<string> $test
     */
    public function __construct(array $test)
    {
        $this->test = $test;
    }
}
