<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Contracts\Violation\ViolationReportableInterface;
use SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;

class ViolationReportGenerator
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger
     */
    protected ViolationReportMerger $violationReportMerger;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface
     */
    protected ViolationReportRepositoryInterface $violationReportRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConvertorResolver
     */
    protected ViolationConvertorResolver $violationConvertorResolver;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger $violationReportMerger
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface $violationReportRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConvertorResolver $violationConvertorResolver
     */
    public function __construct(
        ViolationReportMerger $violationReportMerger,
        ViolationReportRepositoryInterface $violationReportRepository,
        ViolationConvertorResolver $violationConvertorResolver
    ) {
        $this->violationReportMerger = $violationReportMerger;
        $this->violationReportRepository = $violationReportRepository;
        $this->violationConvertorResolver = $violationConvertorResolver;
    }

    /**
     * @param string $taskId
     * @param array<\SprykerSdk\Sdk\Contracts\Entity\CommandInterface> $commands
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    public function collectViolations(string $taskId, array $commands): ?ViolationReportInterface
    {
        /** @var array<\SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface> $violationReports */
        $violationReports = [];
        foreach ($commands as $command) {
            if ($command instanceof ViolationReportableInterface) {
                $violationReport = $command->getViolationReport();
                if ($violationReport) {
                    $violationReports[] = $violationReport;
                }

                continue;
            }

            $violationConvertor = $this->violationConvertorResolver->resolve($command);
            if ($violationConvertor) {
                $violationReports[] = $violationConvertor->convert();
            }
        }

        if (!$violationReports) {
            return null;
        }

        $violationReport = $this->violationReportMerger->merge($violationReports);
        $this->violationReportRepository->save($taskId, $violationReport);

        return $violationReport;
    }
}
