<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

interface ViolationReportFileMapperInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return array
     */
    public function mapViolationReportToYamlStructure(ViolationReportInterface $violationReport): array;

    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return array
     */
    public function mapViolationReportToHtml(ViolationReportInterface $violationReport): array;

    /**
     * @param array $violationReport
     * @param array<string>|null $includePackages
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    public function mapFileStructureToViolationReport(array $violationReport, ?array $includePackages = []): ViolationReportInterface;
}
