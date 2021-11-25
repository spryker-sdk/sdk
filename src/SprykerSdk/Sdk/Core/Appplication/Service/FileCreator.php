<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Dependency\FileCreatorInterface;
use SprykerSdk\Sdk\Contracts\Entity\FileInterface;

class FileCreator implements FileCreatorInterface
{
    public function create(FileInterface $file): void
    {
        file_put_contents($file->getPath(), $file->getContent());
    }
}
