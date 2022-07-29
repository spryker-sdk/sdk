<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service\Violation;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationFix;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use SprykerSdk\SdkContracts\Report\ReportInterface;
use SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportArrayConverterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class ViolationReportArrayConverter implements ViolationReportArrayConverterInterface
{
    /**
     * @var string
     */
    public const VIOLATION_REPORT_TYPE = 'violation_report';

    /**
     * @return string
     */
    public function getSupportedReportType(): string
    {
        return static::VIOLATION_REPORT_TYPE;
    }

    /**
     * @return class-string
     */
    public function getSupportedReportClass(): string
    {
        return ViolationReportInterface::class;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Report\ReportInterface $report
     *
     * @throws \InvalidArgumentException
     *
     * @return array<mixed>
     */
    public function toArray(ReportInterface $report): array
    {
        if (!($report instanceof ViolationReportInterface)) {
            throw new InvalidArgumentException(sprintf('Invalid incoming report type %s', get_class($report)));
        }

        return [
            'type' => static::VIOLATION_REPORT_TYPE,
            'path' => $report->getPath(),
            'project' => $report->getProject(),
            'violations' => array_map([$this, 'violationToArray'], $report->getViolations()),
            'packages' => array_map([$this, 'packageViolationReportToArray'], $report->getPackages()),
        ];
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface $violation
     *
     * @return array<string, mixed>
     */
    protected function packageViolationReportToArray(PackageViolationReportInterface $violation): array
    {
        $fileViolations = [];
        foreach ($violation->getFileViolations() as $key => $violations) {
            $fileViolations[$key] = array_map([$this, 'violationToArray'], $violations);
        }

        return [
            'violations' => array_map([$this, 'violationToArray'], $violation->getViolations()),
            'path' => $violation->getPath(),
            'package' => $violation->getPackage(),
            'file_violations' => $fileViolations,
        ];
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationInterface $violation
     *
     * @return array
     */
    protected function violationToArray(ViolationInterface $violation): array
    {
        return [
            'id' => $violation->getId(),
            'message' => $violation->getMessage(),
            'severity' => $violation->getSeverity(),
            'additional_attributes' => $violation->getAdditionalAttributes(),
            'class' => $violation->getClass(),
            'method' => $violation->getMethod(),
            'start_column' => $violation->getStartColumn(),
            'end_column' => $violation->getEndColumn(),
            'start_line' => $violation->getStartLine(),
            'end_line' => $violation->getEndLine(),
            'is_fixable' => $violation->isFixable(),
            'priority' => $violation->priority(),
            'produced_by' => $violation->producedBy(),
            'fix' => $violation->getFix() ?
                [
                    'type' => $violation->getFix()->getType(),
                    'action' => $violation->getFix()->getAction(),
                ] :
                null,
        ];
    }

    /**
     * @param array $arrayData
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    public function fromArray(array $arrayData): ViolationReportInterface
    {
        if (!isset($arrayData['type']) || $arrayData['type'] !== static::VIOLATION_REPORT_TYPE) {
            throw new InvalidReportTypeException(sprintf('Invalid report type "%s"', $arrayData['type'] ?? '-'));
        }

        if (!isset($arrayData['project'], $arrayData['path'], $arrayData['violations'], $arrayData['packages'])) {
            throw new MissingValueException(sprintf('Invalid report data "%s"', json_encode($arrayData, JSON_THROW_ON_ERROR)));
        }

        return new ViolationReport(
            $arrayData['project'],
            $arrayData['path'],
            array_map([$this, 'violationFromArray'], $arrayData['violations']),
            array_map([$this, 'packageViolationReportFromArray'], $arrayData['packages']),
        );
    }

    /**
     * @param array<string, mixed> $arrayData
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return \SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface
     */
    protected function packageViolationReportFromArray(array $arrayData): PackageViolationReportInterface
    {
        if (!isset($arrayData['package'], $arrayData['path'], $arrayData['violations'], $arrayData['file_violations'])) {
            throw new MissingValueException(sprintf('Invalid input data "%s"', json_encode($arrayData, JSON_THROW_ON_ERROR)));
        }

        $fileViolations = [];

        foreach ($arrayData['file_violations'] as $key => $reports) {
            $fileViolations[(string)$key] = array_map([$this, 'violationFromArray'], $reports);
        }

        return new PackageViolationReport(
            $arrayData['package'],
            $arrayData['path'],
            array_map([$this, 'violationFromArray'], $arrayData['violations']),
            $fileViolations,
        );
    }

    /**
     * @param array $arrayData
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationInterface
     */
    protected function violationFromArray(array $arrayData): ViolationInterface
    {
        if (!isset($arrayData['id'], $arrayData['message'], $arrayData['severity'], $arrayData['is_fixable'], $arrayData['produced_by'])) {
            throw new MissingValueException(sprintf('Invalid input data "%s"', json_encode($arrayData, JSON_THROW_ON_ERROR)));
        }

        return (new Violation($arrayData['id'], $arrayData['message']))
            ->setSeverity($arrayData['severity'])
            ->setPriority($arrayData['priority'] ?? null)
            ->setClass($arrayData['class'] ?? null)
            ->setStartLine($arrayData['start_line'] ?? null)
            ->setEndLine($arrayData['end_line'] ?? null)
            ->setStartColumn($arrayData['start_column'] ?? null)
            ->setEndColumn($arrayData['end_column'] ?? null)
            ->setMethod($arrayData['method'] ?? null)
            ->setAttributes($arrayData['additional_attributes'] ?? [])
            ->setFixable($arrayData['is_fixable'])
            ->setProduced($arrayData['produced_by'])
            ->setFix($arrayData['fix'] ? new ViolationFix($arrayData['fix']['type'], $arrayData['fix']['action']) : null);
    }
}
