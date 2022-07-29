<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Entity\FileInterface;

interface FileManagerInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\FileInterface $file
     *
     * @return void
     */
    public function create(FileInterface $file): void;

    /**
     * @param \SprykerSdk\SdkContracts\Entity\FileInterface $file
     *
     * @return bool
     */
    public function remove(FileInterface $file): bool;
}
