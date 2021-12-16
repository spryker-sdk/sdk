<?php

namespace SprykerSdk\Sdk\Core\Appplication\Violation;

use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

interface ViolationReportFormatterInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return mixed
     */
    public function format(ViolationReportInterface $violationReport): mixed;

    /**
     * @return string
     */
    public function getFormat(): string;
}
