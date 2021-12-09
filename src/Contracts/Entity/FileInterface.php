<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Contracts\Entity;

interface FileInterface
{
    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getContent(): string;
}
