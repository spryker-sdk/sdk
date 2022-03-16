<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Converters;

use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\Violation;
use SprykerSdk\Sdk\Core\Appplication\Dto\Violation\ViolationReport;
use SprykerSdk\Sdk\Core\Appplication\Violation\AbstractViolationConverter;
use SprykerSdk\SdkContracts\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class CodeceptionViolationReportConverter extends AbstractViolationConverter
{
    /**
     * @var string
     */
    private const FAIL = 'fail';

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

        $testCases = json_decode($jsonReport, true);

        if (empty($testCases)) {
            return null;
        }

        $testCases = array_filter($testCases, function($testCase) {
            return $testCase['event'] === 'test';
        });

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            [],
            $this->getPackages($projectDirectory->getValues(), $testCases),
        );
    }

    /**
     * @param string $projectDirectory
     * @param array $testCases
     *
     * @return array<\SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface>
     */
    protected function getPackages(string $projectDirectory, array $testCases): array
    {
        $packages = [];
        foreach ($testCases as $testCase) {
            if ($testCase['status'] !== self::FAIL) {
                continue;
            }

            $relatedPathToFile = ltrim(str_replace($projectDirectory, '', $testCase['trace'][0]['file'] ?? ''), DIRECTORY_SEPARATOR);
            $moduleName = $this->resolveModuleName($relatedPathToFile);
            $pathToModule = $this->resolvePathToModule($relatedPathToFile);
            $classNamespace = $this->resolveClassNamespace($relatedPathToFile);

            $fileViolations = [];
            $fileViolations[$relatedPathToFile][] = new Violation(
                basename($relatedPathToFile, '.php'),
                $testCase['message'],
                ViolationInterface::SEVERITY_ERROR,
                null,
                $classNamespace,
                (int) $testCase['trace'][0]['line'],
                null,
               null,
                null,
                $testCase['trace'][1]['function'] ?? null,
                [],
                false,
                $this->producer,
            );

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
