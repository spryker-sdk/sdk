<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Domain\Entity\FileInterface;
use SprykerSdk\Sdk\Infrastructure\Entity\File;

class FileMapper implements FileMapperInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\FileInterface $file
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Entity\File
     */
    public function mapFile(FileInterface $file): File
    {
        return new File($file->getPath(), $file->getContent());
    }
}
