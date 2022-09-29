<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\SdkContracts\Report\ReportInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

interface ViolationReportRepositoryInterface extends ReportRepositoryInterface
{
    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ReportInterface $violationReport): void;

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface;

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function cleanUp(): void;
}
