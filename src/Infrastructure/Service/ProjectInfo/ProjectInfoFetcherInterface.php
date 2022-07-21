<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo;

interface ProjectInfoFetcherInterface
{
    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo|null
     */
    public function fetchProjectInfo(): ?ProjectInfo;
}
