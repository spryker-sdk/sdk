<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

interface ManifestNormalizerInterface
{
    /**
     * @param string $type
     * @param array<array> $configs
     *
     * @return array<string, array>
     */
    public function validateAndNormalize(string $type, array $configs): array;
}
