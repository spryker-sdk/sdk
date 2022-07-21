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

class ESLintViolationReportConverter extends AbstractViolationConverter
{
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
        $report = array_filter((array)$report);
        if (!$report) {
            return null;
        }

        return new ViolationReport(
            basename($projectDirectory->getValues()),
            '.' . DIRECTORY_SEPARATOR,
            [],
            $this->getPackages($projectDirectory->getValues(), $report),
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
            if (!$file['messages']) {
                continue;
            }

            $relatedPathToFile = ltrim(str_replace($projectDirectory, '', $file['filePath']), DIRECTORY_SEPARATOR);
            $moduleName = $this->resolveModuleName($relatedPathToFile);
            $pathToModule = $this->resolvePathToModule($relatedPathToFile);

            $fileViolations = [];
            foreach ($file['messages'] as $message) {
                $fileViolations[$relatedPathToFile][] = (new Violation(basename($relatedPathToFile), $message['message']))
                    ->setSeverity($this->mapSeverity($message['severity']))
                    ->setStartLine((int)$message['line'])
                    ->setEndLine((int)($message['endLine'] ?? $message['line']))
                    ->setStartColumn((int)$message['column'])
                    ->setEndColumn((int)($message['endColumn'] ?? $message['column']))
                    ->setAttributes($message)
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

    /**
     * Message severity mapping implemented according to ESLint documentation
     *
     * @link https://eslint.org/docs/developer-guide/working-with-custom-formatters#the-message-object
     *
     * @param int $severity
     *
     * @return string
     */
    protected function mapSeverity(int $severity): string
    {
        return [1 => ViolationInterface::SEVERITY_WARNING, 2 => ViolationInterface::SEVERITY_ERROR][$severity] ?? '';
    }
}
