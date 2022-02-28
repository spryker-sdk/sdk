<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converters;

use SimpleXMLElement;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Violation\AbstractViolationConverter;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class PhpBenchReportConverter extends AbstractViolationConverter
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

        $report = simplexml_load_string($report);
        if (!$report instanceof SimpleXMLElement) {
            return null;
        }

        $results = $this->getResults($report);
        if (!$results) {
            return null;
        }

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            $results,
        );
    }

    /**
     * @param \SimpleXMLElement $report
     *
     * @return array<\SprykerSdk\SdkContracts\Violation\ViolationInterface>
     */
    protected function getResults(SimpleXMLElement $report): array
    {
        $results = [];
        foreach ((array)$report->xpath('suite/benchmark') as $benchmark) {
            foreach ((array)$benchmark->xpath('subject/variant/errors/error') as $error) {
                $results[] = new Violation(
                    (string)($benchmark->subject['name'] ?? ''),
                    (string)$error,
                    ViolationInterface::SEVERITY_ERROR,
                    null,
                    (string)($error['exception-class'] ?? ''),
                    (int)($error['line'] ?? ''),
                    null,
                    null,
                    null,
                    null,
                    [],
                    false,
                    $this->producer,
                );
            }

            foreach ((array)$benchmark->xpath('subject/variant/iteration') as $iteration) {
                $results[] = new Violation(
                    (string)($benchmark->subject['name'] ?? ''),
                    (string)($benchmark->subject['name'] ?? ''),
                    ViolationInterface::SEVERITY_INFO,
                    null,
                    (string)($benchmark['class'] ?? ''),
                    null,
                    null,
                    null,
                    null,
                    null,
                    ((array)$iteration)['@attributes'] ?? [],
                    false,
                    $this->producer,
                );
            }
        }

        return $results;
    }
}
