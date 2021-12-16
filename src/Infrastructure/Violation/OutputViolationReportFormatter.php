<?php

namespace SprykerSdk\Sdk\Infrastructure\Violation;

use SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class OutputViolationReportFormatter implements ViolationReportFormatterInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return mixed
     */
    public function format(ViolationReportInterface $violationReport): mixed
    {

    }

    /**
     * @return string
     */
    public function getFormat(): string
    {

    }
}
