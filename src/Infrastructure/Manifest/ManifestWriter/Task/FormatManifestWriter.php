<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task;

use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\ManifestFormatWriterInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class FormatManifestWriter implements ManifestFormatWriterInterface
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @var string
     */
    protected string $fileDirectory;

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param string $fileDirectory
     */
    public function __construct(Filesystem $filesystem, string $fileDirectory)
    {
        $this->filesystem = $filesystem;
        $this->fileDirectory = $fileDirectory;
    }

    /**
     * @param string $fileContent
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     *
     * @return string
     */
    public function write(string $fileContent, ManifestFile $manifestFile): string
    {
        $filePath = rtrim($this->fileDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $manifestFile->getFile();

        $this->filesystem->dumpFile($filePath, $fileContent);

        return $filePath;
    }
}
