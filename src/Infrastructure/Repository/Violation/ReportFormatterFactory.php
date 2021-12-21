<?php

namespace SprykerSdk\Sdk\Infrastructure\Repository\Violation;

use SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface;

class ReportFormatterFactory
{
    /**
     * @var string|null
     */
    protected ?string $format = null;

    /**
     * @var iterable|\SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface[]
     */
    protected iterable $violationReportFormatters;

    /**
     * @param iterable<\SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface> $violationReportFormatters
     */
    public function __construct(iterable $violationReportFormatters)
    {
        $this->violationReportFormatters = $violationReportFormatters;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface|null
     */
    public function getViolationReportFormatter(): ?ViolationReportFormatterInterface
    {
        if (!$this->format) {
            return null;
        }

        foreach ($this->violationReportFormatters as $violationReportFormatter) {
            if ($violationReportFormatter->getFormat() === $this->format) {
                return $violationReportFormatter;
            }
        }

        return null;
    }
}
