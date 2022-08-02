<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Report\ReportInterface;

interface ReportRepositoryInterface
{
    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Report\ReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ReportInterface $violationReport): void;

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportInterface|null
     */
    public function findByTask(string $taskId): ?ReportInterface;

    /**
     * @return void
     */
    public function cleanUp(): void;
}
