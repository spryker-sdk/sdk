<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\SdkContracts\Report\ReportInterface;
use SprykerSdk\SdkContracts\Report\ReportRepositoryInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

interface ViolationReportRepositoryInterface extends ReportRepositoryInterface
{
    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ReportInterface $violationReport): void;

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface;

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function cleanup(): void;
}
