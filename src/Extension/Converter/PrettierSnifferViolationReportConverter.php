<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converter;

use SprykerSdk\Sdk\Core\Application\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Application\Violation\AbstractViolationConverter;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface|null
     */
    public function convert(): ?ViolationReportInterface
    {
        $projectDirectory = $this->settingRepository->findOneByPath(Setting::PATH_PROJECT_DIR);
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
     * @return array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>
     */
    protected function parseViolations(string $report): array
    {
        $violations = [];

        $reportLines = $this->splitReportLines($report);

        foreach ($reportLines as $line) {
            preg_match('/^(?:\[(?<severity>error|warn)\])\s+(?<path>\/?(?:(?:[\w.-]+)\/+)*(?:[\w.-]+)\.(?:[\w.-]+))(?::\s(?<error>\w+):\s(?<message>(?:\w\s*)+)(?:\((?<line>\d+):(?<column>\d+)\)))?/', $line, $matches);
            if (!empty($matches['path'])) {
                $violations[] = (new Violation($matches['path'], $matches['message'] ?? 'File formatting'))
                    ->setSeverity(
                        (isset($matches['severity']) && $matches['severity'] === 'warn') ? ViolationInterface::SEVERITY_WARNING : ViolationInterface::SEVERITY_ERROR,
                    )
                    ->setStartLine(isset($matches['line']) ? (int)$matches['line'] : null)
                    ->setStartColumn(isset($matches['column']) ? (int)$matches['column'] : null)
                    ->setFixable($matches['severity'] === 'error' ? false : true)
                    ->setProduced($this->producer);
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
