<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task\FormatWriter;

use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;
use SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Shared\FormatWriter\AbstractFileManifestFormatWriter;
use SprykerSdk\Sdk\Infrastructure\Manifest\Normalizer\ManifestNormalizerInterface;
use Symfony\Component\Filesystem\Filesystem;

class PhpTaskManifestFormatWriter extends AbstractFileManifestFormatWriter
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Manifest\Normalizer\ManifestNormalizerInterface
     */
    protected ManifestNormalizerInterface $manifestNormalizer;

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param string $fileDirectory
     * @param \SprykerSdk\Sdk\Infrastructure\Manifest\Normalizer\ManifestNormalizerInterface $manifestNormalizer
     */
    public function __construct(Filesystem $filesystem, string $fileDirectory, ManifestNormalizerInterface $manifestNormalizer)
    {
        parent::__construct($filesystem, $fileDirectory);
        $this->manifestNormalizer = $manifestNormalizer;
    }

    /**
     * @param string $fileContent
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     *
     * @return string
     */
    public function write(string $fileContent, ManifestFile $manifestFile): string
    {
        $filePath = parent::write($fileContent, $manifestFile);

        $this->manifestNormalizer->normalize($filePath);

        return $filePath;
    }

    /**
     * @return string
     */
    public function getAcceptableFormat(): string
    {
        return 'php';
    }
}
