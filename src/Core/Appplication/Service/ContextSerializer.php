<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Service;

use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Core\Domain\Entity\Message;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReportConverter;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\MessageInterface;
use SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportConverterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class ContextSerializer
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\ContextInterface $context
     *
     * @return string
     */
    public function serialize(ContextInterface $context): string
    {
        $data = [
            'tags' => $context->getTags(),
            'resolved_values' => $context->getResolvedValues(),
            'messages' => $this->convertMessagesToArray($context->getMessages()),
            'violation_reports' => array_map(function (ViolationReportInterface $report): array {
                return [
                    'path' => $report->getPath(),
                    'project' => $report->getProject(),
                    'violations' => $this->convertViolationsToArray($report->getViolations()),
                    'packages' => $this->convertPackagesToArray($report->getPackages()),
                ];
            }, $context->getViolationReports()),
        ];

        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @param string $content
     *
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    public function deserialize(string $content): ContextInterface
    {
        $context = new Context();
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        if (array_key_exists('tags', $data) && is_array($data['tags'])) {
            $context->setTags($data['tags']);
        }

        if (array_key_exists('resolved_values', $data) && is_array($data['resolved_values'])) {
            $context->setResolvedValues($data['resolved_values']);
        }

        if (array_key_exists('messages', $data) && is_array($data['messages'])) {
            foreach ($data['messages'] as $id => $messageData) {
                if (!$messageData['message']) {
                    continue;
                }

                $context->addMessage(
                    $id,
                    new Message($messageData['message'], $messageData['verbosity'] ?? MessageInterface::INFO),
                );
            }
        }

        if (array_key_exists('violation_reports', $data) && is_array($data['violation_reports'])) {
            foreach ($data['violation_reports'] as $violationReportData) {
                $context->addViolationReport(
                    $this->createViolationReport($violationReportData),
                );
            }
        }

        return $context;
    }

    /**
     * @param array<string, \SprykerSdk\SdkContracts\Entity\MessageInterface> $messages
     *
     * @return array<string, array>
     */
    protected function convertMessagesToArray(array $messages): array
    {
        $messagesData = [];
        foreach ($messages as $id => $message) {
            $messagesData[$id] = [
                'message' => $message->getMessage(),
                'verbosity' => $message->getVerbosity(),
            ];
        }

        return $messagesData;
    }

    /**
     * @param array $reportData
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportConverterInterface
     */
    protected function convertArrayToViolationReportConverter(array $reportData): ViolationReportConverterInterface
    {
        return new ViolationReportConverter(
            $reportData['id'],
            $reportData['message'],
            $reportData['severity'],
            $reportData['priority'],
            $reportData['class'],
            $reportData['start_line'],
            $reportData['end_line'],
            $reportData['start_column'],
            $reportData['end_column'],
            $reportData['method'],
            $reportData['additional_attributes'],
            $reportData['is_fixable'],
            $reportData['produced_by'],
        );
    }

    /**
     * @param array $violationReportData
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    protected function createViolationReport(array $violationReportData): ViolationReportInterface
    {
        $violations = array_map([$this, 'convertArrayToViolationReportConverter'], $violationReportData['violations']);
        $packages = array_map([$this, 'convertArrayToPackageViolationReport'], $violationReportData['packages']);

        return new ViolationReport(
            $violationReportData['project'],
            $violationReportData['path'],
            $violations,
            $packages,
        );
    }

    /**
     * @param array $report
     *
     * @return \SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface
     */
    protected function convertArrayToPackageViolationReport(array $report): PackageViolationReportInterface
    {
        $fileViolations = [];

        foreach ($report['file_violations'] as $key => $reports) {
            $fileViolations[(string)$key] = array_map([$this, 'convertArrayToViolationReportConverter'], $reports);
        }

        return new PackageViolationReport(
            $report['package'],
            $report['path'],
            array_map([$this, 'convertArrayToViolationReportConverter'], $report['violations']),
            $fileViolations,
        );
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportConverterInterface $violationReportConverter
     *
     * @return array
     */
    protected function convertViolationToArray(ViolationReportConverterInterface $violationReportConverter): array
    {
        return [
            'id' => $violationReportConverter->getId(),
            'message' => $violationReportConverter->getMessage(),
            'severity' => $violationReportConverter->getSeverity(),
            'additional_attributes' => $violationReportConverter->getAdditionalAttributes(),
            'class' => $violationReportConverter->getClass(),
            'method' => $violationReportConverter->getMethod(),
            'start_column' => $violationReportConverter->getStartColumn(),
            'end_column' => $violationReportConverter->getEndColumn(),
            'start_line' => $violationReportConverter->getStartLine(),
            'end_line' => $violationReportConverter->getEndLine(),
            'is_fixable' => $violationReportConverter->isFixable(),
            'priority' => $violationReportConverter->priority(),
            'produced_by' => $violationReportConverter->producedBy(),
        ];
    }

    /**
     * @param \SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface $packageViolationReport
     *
     * @return array
     */
    protected function convertPackageToArray(PackageViolationReportInterface $packageViolationReport): array
    {
        $fileViolations = [];
        foreach ($packageViolationReport->getFileViolations() as $key => $reports) {
            $fileViolations[$key] = $this->convertViolationsToArray($reports);
        }

        return [
            'violations' => $this->convertViolationsToArray($packageViolationReport->getViolations()),
            'path' => $packageViolationReport->getPath(),
            'package' => $packageViolationReport->getPackage(),
            'file_violations' => $fileViolations,
        ];
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Violation\ViolationReportConverterInterface> $reports
     *
     * @return array
     */
    protected function convertViolationsToArray(array $reports): array
    {
        return array_map([$this, 'convertViolationToArray'], $reports);
    }

    /**
     * @param array<\SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface> $reports
     *
     * @return array
     */
    protected function convertPackagesToArray(array $reports): array
    {
        return array_map([$this, 'convertPackageToArray'], $reports);
    }
}
