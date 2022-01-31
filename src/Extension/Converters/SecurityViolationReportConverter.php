<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converters;

use SprykerSdk\Sdk\Core\Appplication\Violation\AbstractViolationConverter;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReportConverter;
use SprykerSdk\SdkContracts\Violation\ViolationReportConverterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class SecurityViolationReportConverter extends AbstractViolationConverter
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
        $jsonReport = $this->readFile();

        if (!$jsonReport) {
            return null;
        }
        $report = json_decode($jsonReport, true);

        if (empty($report) && !is_array($report)) {
            return null;
        }

        $violations = [];

        foreach ($report as $packageName => $package) {
            foreach ($package['advisories'] as $advisory) {
                $violations[] = new ViolationReportConverter(
                    uniqid($packageName, true),
                    sprintf('%s - %s', $packageName, $advisory['title']),
                    ViolationReportConverterInterface::SEVERITY_ERROR,
                    null,
                    $package['version'],
                    null,
                    null,
                    null,
                    null,
                    null,
                    [],
                    false,
                    $this->producer,
                );
            }
        }

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            $violations,
        );
    }
}
