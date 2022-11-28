<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Normalizer;

interface ManifestNormalizerInterface
{
    /**
     * @param string $filePath
     *
     * @return void
     */
    public function normalize(string $filePath): void;
}
