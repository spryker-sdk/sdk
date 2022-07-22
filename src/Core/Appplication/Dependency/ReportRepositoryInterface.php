<?php

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

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
    public function cleanup(): void;
}
