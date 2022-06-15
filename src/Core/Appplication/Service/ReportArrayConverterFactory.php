<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException;
use SprykerSdk\SdkContracts\Report\ReportArrayConverterInterface;
use SprykerSdk\SdkContracts\Report\ReportInterface;

class ReportArrayConverterFactory
{
    /**
     * @param iterable<\SprykerSdk\SdkContracts\Report\ReportArrayConverterInterface> $reportArrayConverters
     */
    private iterable $reportArrayConverters;

    /**
     * @param iterable<\SprykerSdk\SdkContracts\Report\ReportArrayConverterInterface> $reportArrayConverters
     */
    public function __construct(iterable $reportArrayConverters)
    {
        $this->reportArrayConverters = $reportArrayConverters;
    }

    /**
     * @param string $type
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportArrayConverterInterface
     */
    public function getArrayConverterByType(string $type): ReportArrayConverterInterface
    {
        foreach ($this->reportArrayConverters as $reportArrayConverter) {
            if ($reportArrayConverter->getSupportedReportType() === $type) {
                return $reportArrayConverter;
            }
        }

        throw new InvalidReportTypeException(sprintf('Invalid report type %s', $type));
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\ReportInterface $report
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportArrayConverterInterface
     */
    public function getArrayConverterByReport(ReportInterface $report): ReportArrayConverterInterface
    {
        foreach ($this->reportArrayConverters as $reportArrayConverter) {
            $supportedReportType = $reportArrayConverter->getSupportedReportClass();
            if ($report instanceof $supportedReportType) {
                return $reportArrayConverter;
            }
        }

        throw new InvalidReportTypeException(sprintf('Invalid report type %s', $report::class));
    }
}
