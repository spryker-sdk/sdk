<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Style;

interface StyleInterface
{
    /**
     * @param string $message
     *
     * @return void
     */
    public function writeLine(string $message): void;
}
