<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Task;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dependency\Manifest\TemplateReaderInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface;

class TaskTemplateReader implements TemplateReaderInterface
{
    /**
     * @var array<\SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\TemplateFormatReaderInterface>
     */
    protected iterable $formatTemplateReaders;

    /**
     * @param iterable<\SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\TemplateFormatReaderInterface> $formatTemplateReaders
     */
    public function __construct(iterable $formatTemplateReaders)
    {
        $this->formatTemplateReaders = $formatTemplateReaders;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface $manifestDto
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function readTemplate(ManifestRequestDtoInterface $manifestDto): string
    {
        $fileFormat = $manifestDto->getManifestFile()->getFormat();

        foreach ($this->formatTemplateReaders as $formatTemplateReader) {
            if ($formatTemplateReader->getAcceptableFormat() !== $fileFormat) {
                continue;
            }

            return $formatTemplateReader->readTemplate($manifestDto);
        }

        throw new InvalidArgumentException(sprintf('Unsupported template reader format `%s`', $fileFormat));
    }
}
