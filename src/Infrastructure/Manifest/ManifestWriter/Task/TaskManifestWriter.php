<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dependency\Manifest\ManifestWriterInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;

class TaskManifestWriter implements ManifestWriterInterface
{
    /**
     * @var iterable<\SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Shared\FormatWriter\ManifestFormatWriterInterface>
     */
    protected iterable $manifestFormatWriters;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Shared\FormatWriter\ManifestFormatWriterInterface> $manifestFormatWriters
     */
    public function __construct(iterable $manifestFormatWriters)
    {
        $this->manifestFormatWriters = $manifestFormatWriters;
    }

    /**
     * @param string $fileContent
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function write(string $fileContent, ManifestFile $manifestFile): string
    {
        foreach ($this->manifestFormatWriters as $manifestFormatWriter) {
            if ($manifestFormatWriter->getAcceptableFormat() !== $manifestFile->getFormat()) {
                continue;
            }

            return $manifestFormatWriter->write($fileContent, $manifestFile);
        }

        throw new InvalidArgumentException(sprintf('Unsupported writer format `%s`', $manifestFile->getFormat()));
    }
}
