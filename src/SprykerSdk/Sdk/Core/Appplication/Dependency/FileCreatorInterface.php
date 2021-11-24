<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Core\Domain\Entity\FileInterface;

interface FileCreatorInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface $file
     *
     * @return void
     */
    public function create(FileInterface $file): void;
}
