<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

interface FilesystemInitInterface
{
    /**
     * @param string $cwd
     *
     * @return void
     */
    public function setcwd(string $cwd): void;
}
