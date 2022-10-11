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
use SprykerSdk\Sdk\Core\Domain\Enum\Setting;
use SprykerSdk\SdkContracts\Report\Violation\ViolationInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

class CodeceptionViolationReportConverter extends AbstractViolationConverter
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

        $jsonReport = $this->readFile();

        if (!$jsonReport) {
            return null;
        }

        $testCases = $this->extractJson($jsonReport);

        if (count($testCases) === 0) {
            return null;
        }

        $testCases = array_filter($testCases, function ($testCase) {
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
     * @param string $json
     *
     * @return array
     */
    protected function splitJson(string $json): array
    {
        $q = false;
        $len = strlen($json);
        $objects = [];
        for ($l = $c = $i = 0; $i < $len; $i++) {
            $json[$i] === '"' && ($i > 0 ? $json[$i - 1] : '') !== '\\' && $q = !$q;
            if (!$q && in_array($json[$i], [' ', "\r", "\n", "\t"])) {
                continue;
            }
            in_array($json[$i], ['{', '[']) && !$q && $l++;
            in_array($json[$i], ['}', ']']) && !$q && $l--;
            (isset($objects[$c]) && $objects[$c] .= $json[$i]) || $objects[$c] = $json[$i];
            $c += ($l === 0);
        }

        return $objects;
    }

    /**
     * @param string $jsonReport
     *
     * @return array
     */
    protected function extractJson(string $jsonReport): array
    {
        $arrays = [];
        $jsons = $this->splitJson($jsonReport);
        foreach ($jsons as $json) {
            $arrays[] = json_decode($json, true);
        }

        return array_merge(...$arrays);
    }

    /**
     * @param string $projectDirectory
     * @param array $testCases
     *
     * @return array<\SprykerSdk\SdkContracts\Report\Violation\PackageViolationReportInterface>
     */
    protected function getPackages(string $projectDirectory, array $testCases): array
    {
        $packages = [];
        foreach ($testCases as $testCase) {
            if ($testCase['status'] !== 'fail') {
                continue;
            }

            $relatedPathToFile = ltrim(str_replace($projectDirectory, '', $testCase['trace'][0]['file'] ?? ''), DIRECTORY_SEPARATOR);
            $moduleName = $this->resolveModuleName($relatedPathToFile);
            $pathToModule = $this->resolvePathToModule($relatedPathToFile);
            $classNamespace = $this->resolveClassNamespace($relatedPathToFile);

            $fileViolations = [];
            $violation = new Violation(
                basename($relatedPathToFile, '.php'),
                $testCase['message'],
            );

            $fileViolations[$relatedPathToFile][] = $violation
                ->setProduced($this->producer)
                ->setFixable(false)
                ->setClass($classNamespace)
                ->setSeverity(ViolationInterface::SEVERITY_ERROR)
                ->setStartLine((int)($testCase['trace'][0]['line'] ?? null))
                ->setMethod($testCase['trace'][1]['function'] ?? null);

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
