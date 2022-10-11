<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Manifest;

use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile;

interface ManifestWriterInterface
{
    /**
     * @param string $fileContent
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestFile $manifestFile
     *
     * @return string
     */
    public function write(string $fileContent, ManifestFile $manifestFile): string;
}
