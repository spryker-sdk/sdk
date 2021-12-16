<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

interface ViolationReportRepositoryInterface
{
    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ViolationReportInterface $violationReport): void;

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface;

    /**
     * @param string $taskId
     * @param array<string> $packageIds
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function findByPackage(string $taskId, array $packageIds): ?ViolationReportInterface;

    /**
     * @param string|null $taskId
     *
     * @return void
     */
    public function cleanupViolationReport(?string $taskId = null): void;
}
