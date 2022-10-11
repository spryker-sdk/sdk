<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency\Service\ProjectInfo;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo;

interface ProjectInfoFetcherInterface
{
    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dto\ProjectInfo\ProjectInfo|null
     */
    public function fetchProjectInfo(): ?ProjectInfo;
}
