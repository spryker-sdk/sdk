<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter;

use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;

interface ManifestFormatWriterInterface
{
    /**
     * @param string $fileContent
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     *
     * @return string
     */
    public function write(string $fileContent, ManifestFile $manifestFile): string;

    /**
     * @return string
     */
    public function getAcceptableFormat(): string;
}
