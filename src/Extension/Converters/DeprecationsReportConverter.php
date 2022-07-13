<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converters;

use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Violation\AbstractViolationConverter;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class DeprecationsReportConverter extends AbstractViolationConverter
{
    /**
     * @var string
     */
    protected string $fileName;

    /**
     * @var string
     */
    protected string $producer;

    /**
     * @param array $configuration
     *
     * @return void
     */
    public function configure(array $configuration): void
    {
        $this->fileName = (string)$configuration['input_file'];
        $this->producer = (string)$configuration['producer'];
    }

    /**
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function convert(): ?ViolationReportInterface
    {
        $projectDirectory = $this->settingRepository->findOneByPath('project_dir');
        if (!$projectDirectory) {
            return null;
        }

        $jsonReport = $this->readFile();
        if (!$jsonReport) {
            return null;
        }

        $report = json_decode($jsonReport, true);
        if (!is_array($report)) {
            return null;
        }

        $deprecations = $this->filterDeprecations($report);
        if (count($deprecations) === 0) {
            return null;
        }

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            $this->formatDeprecations($deprecations),
        );
    }

    /**
     * @param array $report
     *
     * @return array
     */
    protected function filterDeprecations(array $report): array
    {
        return array_filter($report, function (array $issue): bool {
            return strpos($issue['type'] ?? '', 'Deprecated') === 0;
        });
    }

    /**
     * @param array $issues
     *
     * @return array<\SprykerSdk\SdkContracts\Violation\ViolationInterface>
     */
    protected function formatDeprecations(array $issues): array
    {
        return array_map([static::class, 'createDeprecation'], $issues);
    }

    /**
     * @param array $issue
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationInterface
     */
    protected function createDeprecation(array $issue): ViolationInterface
    {
        return (new Violation($issue['file_name'], $issue['message']))
            ->setStartLine($issue['line_from'])
            ->setEndLine($issue['line_to'])
            ->setStartColumn($issue['column_from'])
            ->setEndColumn($issue['column_to'])
            ->setAttributes($issue)
            ->setProduced($this->producer);
    }
}
