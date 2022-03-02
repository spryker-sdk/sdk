<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz;

class Test
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
