<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Mapper;

use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationFix;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationReport;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class ViolationReportFileMapper implements ViolationReportFileMapperInterface
{
    /**
     * @param array $violationReport
     * @param array<string>|null $includePackages
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    public function mapFileStructureToViolationReport(array $violationReport, ?array $includePackages = []): ViolationReportInterface
    {
        $packages = [];
        foreach ($violationReport['packages'] as $packageData) {
            if ($includePackages && !in_array($packageData['id'], $includePackages)) {
                continue;
            }

            $packageViolations = [];
            if (isset($packageData['violations'])) {
                foreach ($packageData['violations'] as $violation) {
                    $packageViolations[] = $this->createViolation($violation);
                }
            }

            $violations = [];
            if (isset($packageData['files'])) {
                foreach ($packageData['files'] as $file) {
                    foreach ($file['violations'] as $violation) {
                        $violations[(string)$file['path']][] = $this->createViolation($violation);
                    }
                }
            }

            $packages[] = new PackageViolationReport(
                $packageData['id'],
                $packageData['path'],
                $packageViolations,
                $violations,
            );
        }

        $projectViolations = [];
        if (isset($violationReport['violations'])) {
            foreach ($violationReport['violations'] as $violation) {
                $projectViolations[] = $this->createViolation($violation);
            }
        }

        return new ViolationReport($violationReport['project'], $violationReport['path'], $projectViolations, $packages);
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return array
     */
    public function mapViolationReportToYamlStructure(ViolationReportInterface $violationReport): array
    {
        $violationReportStructure = [];
        $violationReportStructure['project'] = $violationReport->getProject();
        $violationReportStructure['path'] = $violationReport->getPath();
        $violationReportStructure['violations'] = [];
        foreach ($violationReport->getViolations() as $violation) {
            $violationReportStructure['violations'][] = $this->convertViolationToArray($violation);
        }
        $violationReportStructure['packages'] = [];
        foreach ($violationReport->getPackages() as $package) {
            $files = [];
            foreach ($package->getFileViolations() as $path => $fileViolations) {
                $file = [];
                $file['path'] = $path;
                $file['violations'] = [];
                $violations = array_map(function (ViolationInterface $violation) {
                    return $this->convertViolationToArray($violation);
                }, $fileViolations);
                $file['violations'] = array_merge($file['violations'], $violations);
                $files[] = $file;
            }

            $violationReportStructure['packages'][] = [
                'id' => $package->getPackage(),
                'path' => $package->getPath(),
                'violations' => array_map(function (ViolationInterface $violation) {
                    return $this->convertViolationToArray($violation);
                }, $package->getViolations()),
                'files' => $files,
            ];
        }

        return $violationReportStructure;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationInterface $violation
     *
     * @return array<string, mixed>
     */
    protected function convertViolationToArray(ViolationInterface $violation): array
    {
        $violationData = [];

        $violationData['id'] = $violation->getId();
        $violationData['message'] = $violation->getMessage();
        $violationData['severity'] = $violation->getSeverity();
        $violationData['priority'] = $violation->priority();
        $violationData['class'] = $violation->getClass();
        $violationData['method'] = $violation->getMethod();
        $violationData['start_line'] = $violation->getStartLine();
        $violationData['end_line'] = $violation->getEndLine();
        $violationData['start_column'] = $violation->getStartColumn();
        $violationData['end_column'] = $violation->getStartColumn();
        $violationData['additional_attributes'] = $violation->getAdditionalAttributes();
        $violationData['fixable'] = $violation->isFixable();
        $violationData['produced_by'] = $violation->producedBy();
        $violationData['fix'] = $violation->getFix() ?
        [
            'type' => $violation->getFix()->getType(),
            'action' => $violation->getFix()->getAction(),
        ] :
        null;

        return $violationData;
    }

    /**
     * @param array $violation
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationInterface
     */
    protected function createViolation(array $violation): ViolationInterface
    {
        return (new Violation(
            $violation['id'],
            $violation['message'],
        )
        )
            ->setSeverity($violation['severity'] ?? ViolationInterface::SEVERITY_ERROR)
            ->setPriority($violation['priority'] ?? null)
            ->setClass($violation['class'] ?? null)
            ->setMethod($violation['method'] ?: null)
            ->setStartLine($violation['start_line'] ?? null)
            ->setEndLine($violation['end_line'] ?? null)
            ->setStartColumn($violation['start_column'] ?? null)
            ->setEndColumn($violation['end_column'] ?? null)
            ->setAttributes($violation['additional_attributes'])
            ->setFixable($violation['fixable'] ?? false)
            ->setProduced($violation['produced_by'] ?? '')
            ->setFix(isset($violation['fix']) ? new ViolationFix($violation['fix']['type'], $violation['fix']['action']) : null);
    }
}
