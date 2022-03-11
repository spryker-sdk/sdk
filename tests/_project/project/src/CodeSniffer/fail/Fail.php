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
    protected array $tests;

    /**
     * @param array<string> $tests
     */
    public function __construct(array $tests)
    {
        $this->tests = $tests;
    }
}
