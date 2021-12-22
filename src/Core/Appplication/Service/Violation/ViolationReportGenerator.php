<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportableInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

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
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver
     */
    protected ViolationConverterResolver $violationConverterResolver;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger $violationReportMerger
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface $violationReportRepository
     * @param \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver $violationConverterResolver
     */
    public function __construct(
        ViolationReportMerger $violationReportMerger,
        ViolationReportRepositoryInterface $violationReportRepository,
        ViolationConverterResolver $violationConverterResolver
    ) {
        $this->violationReportMerger = $violationReportMerger;
        $this->violationReportRepository = $violationReportRepository;
        $this->violationConverterResolver = $violationConverterResolver;
    }

    /**
     * @param string $taskId
     * @param array<\SprykerSdk\SdkContracts\Entity\CommandInterface> $commands
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function collectViolations(string $taskId, array $commands): ?ViolationReportInterface
    {
        /** @var array<\SprykerSdk\SdkContracts\Violation\ViolationReportInterface> $violationReports */
        $violationReports = [];

        foreach ($commands as $command) {
            if ($command instanceof ViolationReportableInterface) {
                $violationReport = $command->getViolationReport();
                if ($violationReport) {
                    $violationReports[] = $violationReport;
                }

                continue;
            }

            $violationConverter = $this->violationConverterResolver->resolve($command);
            if ($violationConverter) {
                $violationReports[] = $violationConverter->convert();
            }
        }
        $violationReports = array_filter($violationReports);

        if (!$violationReports) {
            return null;
        }

        $violationReport = $this->violationReportMerger->merge(array_filter($violationReports));

        $this->violationReportRepository->save($taskId, $violationReport);

        return $violationReport;
    }
}
