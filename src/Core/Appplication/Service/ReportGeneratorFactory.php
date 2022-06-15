<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Report\ReportGeneratorFactoryInterface;
use SprykerSdk\SdkContracts\Report\ReportGeneratorInterface;

class ReportGeneratorFactory implements ReportGeneratorFactoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator
     */
    private ViolationReportGenerator $violationReportGenerator;

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
     * @return \SprykerSdk\SdkContracts\Report\ReportGeneratorInterface
     */
    public function getReportGeneratorByContext(ContextInterface $context): ReportGeneratorInterface
    {
        //This is extension point for getting new generators
        return $this->violationReportGenerator;
    }
}
