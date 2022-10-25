<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task\FormatWriter;

use SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Shared\FormatWriter\AbstractFileManifestFormatWriter;

class PhpTaskManifestFormatWriter extends AbstractFileManifestFormatWriter
{
    /**
     * @return string
     */
    public function getAcceptableFormat(): string
    {
        return 'php';
    }
}
