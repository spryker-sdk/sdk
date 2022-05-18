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

class FrontendSnifferViolationReportConverter extends AbstractViolationConverter
{
    /**
     * @var int
     */
    protected const COLOR_ERROR = 31;

    /**
     * @var int
     */
    protected const COLOR_WARNING = 33;

    /**
     * @var int
     */
    protected const COLOR_SUCCESS = 32;

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
        $reportLinesCount = count($reportLines);

        $offset = 0;
        while ($offset < $reportLinesCount) {
            $this->parseBlock($reportLines, $offset, $violations);
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

    /**
     * @param array $reportLines
     * @param int $offset
     * @param array<\SprykerSdk\SdkContracts\Violation\ViolationInterface> $violations
     *
     * @return void
     */
    protected function parseBlock(array $reportLines, int &$offset, array &$violations): void
    {
        $lineColor = $this->getLineColor($reportLines[$offset]);
        $lineRuleName = $this->getLineRuleName($reportLines[$offset]);

        if ($lineRuleName && in_array($lineColor, [static::COLOR_WARNING, static::COLOR_ERROR], true)) {
            $offset++;
            $this->parseRule($reportLines, $offset, $violations);
        }

        $offset++;
    }

    /**
     * @param array $reportLines
     * @param int $offset
     * @param array<\SprykerSdk\SdkContracts\Violation\ViolationInterface> $violations
     *
     * @return void
     */
    protected function parseRule(array $reportLines, int &$offset, array &$violations): void
    {
        $reportLinesCount = count($reportLines);
        while ($offset < $reportLinesCount) {
            $lineColor = $this->getLineColor($reportLines[$offset]);
            $lineIsEmpty = $this->isLineEmpty($reportLines[$offset]);
            $lineRuleName = $this->getLineRuleName($reportLines[$offset]);

            if ($lineRuleName || $lineIsEmpty) {
                break;
            }

            if ($lineColor === static::COLOR_WARNING) {
                $this->parseWarning($reportLines, $offset, $violations);
            } elseif ($lineColor === static::COLOR_ERROR) {
                $this->parseError($reportLines, $offset, $violations);
            } else {
                break;
            }

            $offset++;
        }
    }

    /**
     * @param array $reportLines
     * @param int $offset
     * @param array<\SprykerSdk\SdkContracts\Violation\ViolationInterface> $violations
     *
     * @return void
     */
    protected function parseWarning(array $reportLines, int &$offset, array &$violations): void
    {
        $cleanLine = $this->stripColors($reportLines[$offset]);
        if (preg_match('/^(?<id>[\w-]+)/', $cleanLine, $matches)) {
            $violations[] = (new Violation($matches['id'] ?? '', $cleanLine))
                ->setSeverity(ViolationInterface::SEVERITY_WARNING)
                ->setProduced($this->producer);
        }
    }

    /**
     * @param array $reportLines
     * @param int $offset
     * @param array<\SprykerSdk\SdkContracts\Violation\ViolationInterface> $violations
     *
     * @return void
     */
    protected function parseError(array $reportLines, int &$offset, array &$violations): void
    {
        $firstLine = $this->stripColors($reportLines[$offset]);
        if (preg_match('/(?<message>.* (?<type>[\w-]+) (?<name>[\w-]+)):$/', $firstLine, $matches)) {
            $secondLine = $this->stripColors($reportLines[++$offset]);
            $violations[] = (new Violation($matches['name'] ?? '', $matches['message'] ?? ''))
                ->setClass($secondLine)
                ->setProduced($this->producer);
        }
    }

    /**
     * @param string $report
     *
     * @return string
     */
    protected function stripColors(string $report): string
    {
        return preg_replace('/\x1B\[\d+m/', '', $report) ?? '';
    }

    /**
     * @param string $reportLine
     *
     * @return int|null
     */
    protected function getLineColor(string $reportLine): ?int
    {
        if (preg_match('/^\x1B\[(?<color>\d+)m/', $reportLine, $matches)) {
            return (int)$matches['color'];
        }

        return null;
    }

    /**
     * @param string $reportLine
     *
     * @return string|null
     */
    protected function getLineRuleName(string $reportLine): ?string
    {
        $cleanLine = $this->stripColors($reportLine);
        if (preg_match('/^Rule (?<name>[\w-]+) (?<status>[\w-]+)/', $cleanLine, $matches)) {
            return $matches['name'] ?? '';
        }

        return null;
    }

    /**
     * @param string $reportLine
     *
     * @return bool
     */
    protected function isLineEmpty(string $reportLine): bool
    {
        $cleanLine = $this->stripColors($reportLine);

        return !$cleanLine || $cleanLine[0] === ' ';
    }
}
