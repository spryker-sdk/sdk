<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converters;

use SprykerSdk\Sdk\Core\Appplication\Violation\AbstractViolationConverter;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReportConverter;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class PHPMDViolationConverter extends AbstractViolationConverter
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
        $reportDirectory = $this->settingRepository->findOneByPath('report_dir');
        $projectDirectory = $this->settingRepository->findOneByPath('project_dir');

        if (!$projectDirectory || !$reportDirectory) {
            return null;
        }

        $reportDirectory = $reportDirectory->getValues() . DIRECTORY_SEPARATOR . $this->fileName;

        $jsonReport = file_get_contents($reportDirectory);
        if (!$jsonReport) {
            return null;
        }
        $report = json_decode($jsonReport, true);

        if (empty($report['files'])) {
            return null;
        }

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            [],
            $this->getPackages($projectDirectory->getValues(), $report['files']),
        );
    }

    /**
     * @param string $projectDirectory
     * @param array $files
     *
     * @return array<\SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface>
     */
    protected function getPackages(string $projectDirectory, array $files): array
    {
        $packages = [];
        foreach ($files as $file) {
            $path = $file['file'];
            $relatedPathToFile = ltrim(str_replace($projectDirectory, '', $path), DIRECTORY_SEPARATOR);
            preg_match('~(Zed|Glue)/(\w+)/~', $relatedPathToFile, $matches);
            $moduleName = $matches[2];
            preg_match('~(\w+/)+(Zed|Glue)/(\w+)~', $relatedPathToFile, $matches);
            $pathToModule = $matches[0];
            preg_match('~/(Zed|Glue)/([a-zA-Z/]+)~', $relatedPathToFile, $matches);
            $classNamespace = str_replace(DIRECTORY_SEPARATOR, '\\', $matches[0]);

            $fileViolations = [];
            foreach ($file['violations'] as $violation) {
                $fileViolations[$relatedPathToFile][] = new ViolationReportConverter(
                    basename($relatedPathToFile, '.php'),
                    $violation['description'],
                    $violation['priority'],
                    $classNamespace,
                    (int)$violation['beginLine'],
                    (int)$violation['endLine'],
                    null,
                    null,
                    $violation['method'],
                    $violation,
                    false,
                    $this->producer,
                );
            }

            $packages[] = new PackageViolationReport(
                $moduleName,
                $pathToModule,
                [],
                $fileViolations,
            );
        }

        return $packages;
    }
}
