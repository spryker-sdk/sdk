<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Core\Appplication\Service\Report\ReportGeneratorInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\Report\ReportGeneratorResolverInterface;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

class ViolationReportGeneratorResolver implements ReportGeneratorResolverInterface
{
    protected ViolationReportGenerator $violationReportGenerator;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator $violationReportGenerator
     */
    public function __construct(ViolationReportGenerator $violationReportGenerator)
    {
        $this->violationReportGenerator = $violationReportGenerator;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\Report\ReportGeneratorInterface|null
     */
    public function resolveByContext(ContextInterface $context): ?ReportGeneratorInterface
    {
        return $this->violationReportGenerator;
    }
}
