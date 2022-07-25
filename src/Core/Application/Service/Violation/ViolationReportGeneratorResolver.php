<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\Violation;

use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Report\ReportGeneratorInterface;
use SprykerSdk\SdkContracts\Report\ReportGeneratorResolverInterface;

class ViolationReportGeneratorResolver implements ReportGeneratorResolverInterface
{
    protected ViolationReportGenerator $violationReportGenerator;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportGenerator $violationReportGenerator
     */
    public function __construct(ViolationReportGenerator $violationReportGenerator)
    {
        $this->violationReportGenerator = $violationReportGenerator;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportGeneratorInterface|null
     */
    public function resolveByContext(ContextInterface $context): ?ReportGeneratorInterface
    {
        return $this->violationReportGenerator;
    }
}
