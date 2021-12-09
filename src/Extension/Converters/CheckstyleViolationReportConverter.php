<?php

namespace SprykerSdk\Sdk\Extension\Converters;

use SprykerSdk\Sdk\Contracts\Violation\ViolationConverterInterface;
use SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;

class CheckstyleViolationReportConverter implements ViolationConverterInterface
{
    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var string
     */
    protected string $producer;

    /**
     * @param array $configuration
     *
     * @return void
     */
    public function configure(array $configuration): void
    {
        $this->fileName = $configuration['input_file'];
        $this->producer = $configuration['producer'];
    }

    /**
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface
     */
    public function convert(): ViolationReportInterface
    {
        return new ViolationReport('suite', './', [], []);
    }
}
