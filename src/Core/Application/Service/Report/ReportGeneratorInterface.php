<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\Report;

use SprykerSdk\SdkContracts\Report\ReportInterface;

interface ReportGeneratorInterface
{
    /**
     * @param string $taskId
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportInterface|null
     */
    public function collectReports(string $taskId, array $commands): ?ReportInterface;
}
