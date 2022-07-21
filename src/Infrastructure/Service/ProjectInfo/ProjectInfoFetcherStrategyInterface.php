<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo;

interface ProjectInfoFetcherStrategyInterface
{
    /**
     * @throws \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\FetchDataException
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Service\ProjectInfo\ProjectInfo
     */
    public function fetchProjectInfo(): ProjectInfo;
}