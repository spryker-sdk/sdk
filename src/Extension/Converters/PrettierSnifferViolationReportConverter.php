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

class PrettierSnifferViolationReportConverter extends AbstractViolationConverter
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
        $this->fileName = $configuration['input_file'];
        $this->producer = $configuration['producer'];
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

        $report = $this->readFile();
        if (!$report) {
            return null;
        }

        $violations = $this->parseViolations($report);
        if (!$violations) {
            return null;
        }

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            $violations,
        );
    }

    /**
     * @param string $report
     *
     * @return array<\SprykerSdk\SdkContracts\Violation\ViolationInterface>
     */
    protected function parseViolations(string $report): array
    {
        $violations = [];

        $reportLines = $this->splitReportLines($report);

        foreach ($reportLines as $line) {
            preg_match('/^(?:\[(?<severity>error|warn)\])\s+(?<path>\/?(?:(?:[\w.-]+)\/+)*(?:[\w.-]+)\.(?:[\w.-]+))(?::\s(?<error>\w+):\s(?<message>(?:\w\s*)+)(?:\((?<line>\d+):(?<column>\d+)\)))?/', $line, $matches);
            if (!empty($matches['path'])) {
                $violations[] = new Violation(
                    $matches['path'],
                    $matches['message'] ?? 'File formatting',
                    match ($matches['severity']) {
                        'warn' => ViolationInterface::SEVERITY_WARNING,
                        default => ViolationInterface::SEVERITY_ERROR
                    },
                    null,
                    null,
                    $matches['line'] ?? null,
                    null,
                    $matches['column'] ?? null,
                    null,
                    null,
                    [],
                    $matches['severity'] === 'error' ? false : true,
                    $this->producer,
                );
            }
        }

        return $violations;
    }

    /**
     * @param string $report
     *
     * @return array<string>
     */
    protected function splitReportLines(string $report): array
    {
        return preg_split('/[\n\r]+/', $report) ?: [];
    }
}
