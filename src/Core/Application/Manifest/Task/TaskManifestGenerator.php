<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Manifest\Task;

use SprykerSdk\Sdk\Core\Application\Dependency\Manifest\ManifestWriterInterface;
use SprykerSdk\Sdk\Core\Application\Dependency\Manifest\TemplateReaderInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestResponseDtoInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\TaskManifestResponseDto;
use SprykerSdk\Sdk\Core\Application\Manifest\ManifestGeneratorInterface;

class TaskManifestGenerator implements ManifestGeneratorInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Manifest\ManifestWriterInterface
     */
    protected ManifestWriterInterface $manifestWriter;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\Manifest\TemplateReaderInterface
     */
    protected TemplateReaderInterface $templateReader;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Manifest\ManifestWriterInterface $manifestWriter
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\Manifest\TemplateReaderInterface $templateReader
     */
    public function __construct(ManifestWriterInterface $manifestWriter, TemplateReaderInterface $templateReader)
    {
        $this->manifestWriter = $manifestWriter;
        $this->templateReader = $templateReader;
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface $manifestDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestResponseDtoInterface
     */
    public function generate(ManifestRequestDtoInterface $manifestDto): ManifestResponseDtoInterface
    {
        $filePath = $this->manifestWriter->write(
            $this->templateReader->readTemplate($manifestDto),
            $manifestDto->getManifestFile(),
        );

        return new TaskManifestResponseDto($filePath);
    }
}
