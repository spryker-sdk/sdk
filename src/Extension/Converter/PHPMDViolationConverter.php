<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converter;

use SprykerSdk\Sdk\Core\Application\Dto\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Application\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Application\Violation\AbstractViolationConverter;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

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
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface|null
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
     * @return array<\SprykerSdk\SdkContracts\Report\Violation\PackageViolationReportInterface>
     */
    protected function getPackages(string $projectDirectory, array $files): array
    {
        $packages = [];
        foreach ($files as $file) {
            $path = $file['file'];
            $relatedPathToFile = ltrim(str_replace($projectDirectory, '', $path), DIRECTORY_SEPARATOR);
            $moduleName = $this->resolveModuleName($relatedPathToFile);
            $pathToModule = $this->resolvePathToModule($relatedPathToFile);
            $classNamespace = $this->resolveClassNamespace($relatedPathToFile);

            $fileViolations = [];
            foreach ($file['violations'] as $violation) {
                $fileViolations[$relatedPathToFile][] = (new Violation(basename($relatedPathToFile, '.php'), $violation['description']))
                    ->setPriority($violation['priority'])
                    ->setClass($classNamespace)
                    ->setStartLine($violation['beginLine'])
                    ->setEndLine($violation['endLine'])
                    ->setMethod($violation['method'])
                    ->setAttributes($violation)
                    ->setProduced($this->producer);
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
