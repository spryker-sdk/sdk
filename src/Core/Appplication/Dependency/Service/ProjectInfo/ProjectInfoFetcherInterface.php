<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency\Service\ProjectInfo;

use SprykerSdk\Sdk\Core\Appplication\Dto\ProjectInfo\ProjectInfo;

interface ProjectInfoFetcherInterface
{
    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Dto\ProjectInfo\ProjectInfo|null
     */
    public function fetchProjectInfo(): ?ProjectInfo;
}
