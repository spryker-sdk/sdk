<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface;

interface ViolationReportRepositoryInterface
{
    /**
     * @param string $taskId
     * @param \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ViolationReportInterface $violationReport): void;

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface;

    /**
     * @param string $taskId
     * @param string $package
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    public function findByPackage(string $taskId, string $package): ?ViolationReportInterface;

    /**
     * @param string|null $taskId
     *
     * @return void
     */
    public function cleanupViolationReport(?string $taskId = null): void;
}
