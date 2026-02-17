<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converter;

use Closure;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Application\Violation\AbstractViolationConverter;
use SprykerSdk\SdkContracts\Enum\Setting;
use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

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
     * {@inheritDoc}
     *
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
            return isset($issue['type']) && strpos($issue['type'], 'Deprecated') !== false;
        });
    }

    /**
     * @param array $issues
     *
     * @return array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>
     */
    protected function formatDeprecations(array $issues): array
    {
        return array_map(Closure::fromCallable([$this, 'createDeprecation']), $issues);
    }

    /**
     * @param array $issue
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationInterface
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
