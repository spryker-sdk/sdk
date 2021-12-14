<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converters;

use SprykerSdk\Sdk\Contracts\Violation\AbstractViolationConverter;
use SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\Violation;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;

class CheckstyleViolationReportConverter extends AbstractViolationConverter
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
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
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
     * @return array<\SprykerSdk\Sdk\Contracts\Violation\PackageViolationReportInterface>
     */
    protected function getPackages(string $projectDirectory, array $files): array
    {
        $packages = [];
        foreach ($files as $path => $file) {
            if (!$file['errors']) {
                continue;
            }

            $relatedPathToFile = ltrim(str_replace($projectDirectory, '', $path), DIRECTORY_SEPARATOR);
            preg_match('~(Zed|Glue)/(\w+)/~', $relatedPathToFile, $matches);
            $moduleName = $matches[2];
            preg_match('~(\w+/)+(Zed|Glue)/(\w+)~', $relatedPathToFile, $matches);
            $pathToModule = $matches[0];
            preg_match('~/(Zed|Glue)/([a-zA-Z/]+)~', $relatedPathToFile, $matches);
            $classNamespace = str_replace(DIRECTORY_SEPARATOR, '\\', $matches[0]);

            $fileViolations = [];
            foreach ($file['messages'] as $message) {
                $fileViolations[$relatedPathToFile][] = new Violation(
                    basename($relatedPathToFile, '.php'),
                    $message['message'],
                    null,
                    $classNamespace,
                    (int)$message['line'],
                    (int)$message['line'],
                    (int)$message['column'],
                    (int)$message['column'],
                    null,
                    $message,
                    $message['fixable'],
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
