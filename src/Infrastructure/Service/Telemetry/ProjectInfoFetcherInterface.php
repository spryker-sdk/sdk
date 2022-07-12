<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Service\Telemetry;

interface ProjectInfoFetcherInterface
{
    /**
     * @return string|null
     */
    public function getProjectName(): ?string;
}
