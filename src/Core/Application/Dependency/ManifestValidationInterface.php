<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

interface ManifestValidationInterface
{
    /**
     * @param string $entity
     * @param array<string, array> $configs
     *
     * @return array<string, array>
     */
    public function validate(string $entity, array $configs): array;
}
