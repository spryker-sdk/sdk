<?php

namespace SprykerSdk\Sdk\Core\Appplication\Violation;

use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

interface ViolationReportFormatterInterface
{
    /**
     * @return string
     */
    public function getFormat(): string;

    /**
     * @param string $name
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return mixed
     */
    public function format(string $name, ViolationReportInterface $violationReport): void;

    /**
     * @param string $name
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function read(string $name): ?ViolationReportInterface;
}
