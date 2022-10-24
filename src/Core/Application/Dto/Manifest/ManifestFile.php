<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Manifest;

class ManifestFile
{
    /**
     * @var string
     */
    protected string $format;

    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @param string $format
     * @param string $fileName
     */
    public function __construct(string $format, string $fileName)
    {
        $this->format = $format;
        $this->fileName = ucfirst($fileName);
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return sprintf('%s.%s', $this->getFileName(), $this->getFormat());
    }
}
