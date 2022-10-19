<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Task\FormatReader;

use SprykerSdk\Sdk\Infrastructure\Manifest\TemplateReader\Shared\FormatReader\AbstractFileTemplateFormatReader;

class PhpTaskTemplateFormatReader extends AbstractFileTemplateFormatReader
{
    /**
     * @return string
     */
    public function getAcceptableFormat(): string
    {
        return 'php';
    }
}
