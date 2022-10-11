<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\ManifestWriter\Task;

use SprykerSdk\Sdk\Core\Application\Enum\Manifest\ManifestFileFormatEnum;

class YamlTaskManifestWriter extends FormatManifestWriter
{
    /**
     * @return string
     */
    public function getAcceptableFormat(): string
    {
        return ManifestFileFormatEnum::YAML;
    }
}
