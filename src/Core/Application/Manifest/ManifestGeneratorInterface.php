<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Manifest;

use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface;
use SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestResponseDtoInterface;

interface ManifestGeneratorInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestRequestDtoInterface $manifestDto
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\Manifest\ManifestResponseDtoInterface
     */
    public function generate(ManifestRequestDtoInterface $manifestDto): ManifestResponseDtoInterface;
}
