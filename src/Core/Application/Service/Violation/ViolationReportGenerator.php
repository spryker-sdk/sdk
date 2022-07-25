<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Service\Violation;

use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ConverterResolver;
use SprykerSdk\SdkContracts\Report\ReportGeneratorInterface;
use SprykerSdk\SdkContracts\Violation\ViolationConverterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportableInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class ViolationReportGenerator implements ReportGeneratorInterface
{
    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportMerger
     */
    protected ViolationReportMerger $violationReportMerger;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface
     */
    protected ViolationReportRepositoryInterface $violationReportRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Service\ConverterResolver
     */
    protected ConverterResolver $violationConverterResolver;

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportMerger $violationReportMerger
     * @param \SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface $violationReportRepository
     * @param \SprykerSdk\Sdk\Core\Application\Service\ConverterResolver $violationConverterResolver
     */
    public function __construct(
        ViolationReportMerger $violationReportMerger,
        ViolationReportRepositoryInterface $violationReportRepository,
        ConverterResolver $violationConverterResolver
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
    public function collectReports(string $taskId, array $commands): ?ViolationReportInterface
    {
        /** @var array<\SprykerSdk\SdkContracts\Violation\ViolationReportInterface> $violationReports */
        $violationReports = [];
        foreach ($commands as $command) {
            if ($command instanceof ViolationReportableInterface) {
                $violationReport = $command->getReport();
                if ($violationReport) {
                    $violationReports[] = $violationReport;
                }

                continue;
            }

            $violationConverter = $this->violationConverterResolver->resolve($command);
            if ($violationConverter instanceof ViolationConverterInterface) {
                $violationReports[] = $violationConverter->convert();
            }
        }
        $violationReports = array_filter($violationReports);

        if (!$violationReports) {
            return null;
        }

        $violationReport = $this->violationReportMerger->merge($violationReports);

        $this->violationReportRepository->save($taskId, $violationReport);

        return $violationReport;
    }
}
