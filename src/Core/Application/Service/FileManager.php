<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service;

use SprykerSdk\Sdk\Core\Application\Dependency\FileManagerInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\FileInterface;

class FileManager implements FileManagerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface $file
     *
     * @return void
     */
    public function create(FileInterface $file): void
    {
        file_put_contents($file->getPath(), $file->getContent());
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface $file
     *
     * @return bool
     */
    public function remove(FileInterface $file): bool
    {
        if (file_exists($file->getPath())) {
            return unlink($file->getPath());
        }

        return false;
    }
}
