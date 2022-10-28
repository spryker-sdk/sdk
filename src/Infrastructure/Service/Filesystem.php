<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

class Filesystem extends SymfonyFilesystem implements FilesystemInitInterface
{
    /**
     * @var string
     */
    protected string $cwd;

    /**
     * @return string
     */
    public function getcwd(): string
    {
        return $this->cwd;
    }

    /**
     * @param string $cwd
     *
     * @return void
     */
    public function setcwd(string $cwd): void
    {
        $this->cwd = $cwd;
    }
}
