<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\FileInterface;

class File implements FileInterface
{
    protected string $path;

    protected string $content;

    /**
     * @param string $path
     * @param string $content
     */
    public function __construct(string $path, string $content)
    {
        $this->path = $path;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
