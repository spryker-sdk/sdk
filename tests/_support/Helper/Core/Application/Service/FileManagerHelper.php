<?php

/*
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Core\Application\Service;

use Codeception\Module;
use org\bovigo\vfs\vfsStreamFile;
use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\SdkContracts\Entity\FileInterface;

class FileManagerHelper extends Module
{
    /**
     * @param string $path
     * @param string $content
     *
     * @return \SprykerSdk\SdkContracts\Entity\FileInterface
     */
    public function createFile(string $path, string $content): FileInterface
    {
        return new File($path, $content);
    }

    /**
     * @param string $fileName
     * @param string $content
     *
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    public function createVfsStreamFile(string $fileName, string $content): vfsStreamFile
    {
        $vfsFile = new vfsStreamFile($fileName);
        $vfsFile->setContent($content);

        return $vfsFile;
    }
}
