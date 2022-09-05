<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository\Violation;

use SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface;
use SprykerSdk\Sdk\Core\Application\Violation\ViolationReportFormatterInterface;

class ReportFormatterFactory
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface
     */
    protected ContextFactoryInterface $contextFactory;

    /**
     * @var iterable<\SprykerSdk\Sdk\Core\Application\Violation\ViolationReportFormatterInterface>
     */
    protected iterable $violationReportFormatters;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ContextFactoryInterface $contextFactory
     * @param iterable<\SprykerSdk\Sdk\Core\Application\Violation\ViolationReportFormatterInterface> $violationReportFormatters
     */
    public function __construct(ContextFactoryInterface $contextFactory, iterable $violationReportFormatters)
    {
        $this->contextFactory = $contextFactory;
        $this->violationReportFormatters = $violationReportFormatters;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Violation\ViolationReportFormatterInterface|null
     */
    public function getViolationReportFormatter(): ?ViolationReportFormatterInterface
    {
        $format = $this->contextFactory->getContext()->getFormat();

        foreach ($this->violationReportFormatters as $violationReportFormatter) {
            if ($violationReportFormatter->getFormat() === $format) {
                return $violationReportFormatter;
            }
        }

        return null;
    }
}
