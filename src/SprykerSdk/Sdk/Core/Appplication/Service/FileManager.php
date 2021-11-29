<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Contracts\Entity\FileInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\FileManagerInterface;

class FileManager implements FileManagerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface $file
     *
     * @return void
     */
    public function create(FileInterface $file): void
    {
        file_put_contents($file->getPath(), $file->getContent());
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Entity\FileInterface $file
     *
     * @return void
     */
    public function remove(FileInterface $file): bool
    {
        if (file_exists($file->getPath())) {
            return unlink($file->getPath());
        }

        return false;
    }
}
