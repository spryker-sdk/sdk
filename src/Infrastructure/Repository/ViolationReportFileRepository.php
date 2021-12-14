<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Exception;
use RecursiveIteratorIterator;
use SprykerSdk\Sdk\Contracts\Violation\ViolationInterface;
use SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\PackageViolationReport;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\Violation;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Yaml\Yaml;

class ViolationReportFileRepository implements ViolationReportRepositoryInterface
{
    /**
     * @var string
     */
    protected const REPORT_DIR_SETTING_NAME = 'report_dir';

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface
     */
    protected ProjectSettingRepositoryInterface $projectSettingRepository;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        Yaml $yamlParser
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->yamlParser = $yamlParser;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ViolationReportInterface $violationReport): void
    {
        $violationReportStructure = [];
        $violationReportStructure['project'] = $violationReport->getProject();
        $violationReportStructure['path'] = $violationReport->getPath();
        $violationReportStructure['violations'] = [];
        foreach ($violationReport->getViolations() as $violation) {
            $violationReportStructure['violations'] = $this->convertViolationToArray($violation);
        }
        $violationReportStructure['packages'] = [];
        foreach ($violationReport->getPackages() as $package) {
            $files = [];
            foreach ($package->getFileViolations() as $path => $fileViolations) {
                $file = [];
                $file['path'] = $path;
                $file['violations'] = [];
                $violations = array_map(function (ViolationInterface $violation) {
                    return $this->convertViolationToArray($violation);
                }, $fileViolations);
                $file['violations'] = array_merge($file['violations'], $violations);
                $files[] = $file;
            }

            $violationReportStructure['packages'][] = [
                'id' => $package->getPackage(),
                'path' => $package->getPath(),
                'violations' => array_map(function (ViolationInterface $violation) {
                    return $this->convertViolationToArray($violation);
                }, $package->getViolations()),
                'files' => $files,
            ];
        }

        file_put_contents($this->getViolationReportPath($taskId), $this->yamlParser->dump($violationReportStructure));
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface
    {
        return $this->getViolationReport($taskId);
    }

    /**
     * @param string $taskId
     * @param string $package
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface|null
     */
    public function findByPackage(string $taskId, string $package): ?ViolationReportInterface
    {
        return $this->getViolationReport($taskId, $package);
    }

    /**
     * @param string $taskId
     * @param string|null $package
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationReportInterface
     */
    protected function getViolationReport(string $taskId, ?string $package = null): ViolationReportInterface
    {
        $violationReportData = $this->yamlParser->parseFile($this->getViolationReportPath($taskId));

        $packages = [];
        foreach ($violationReportData['packages'] as $packageData) {
            if ($package && $packageData['id'] !== $package) {
                continue;
            }

            $packageViolations = [];
            if (isset($packageData['violations'])) {
                foreach ($packageData['violations'] as $violation) {
                    $packageViolations[] = $this->createViolation($violation);
                }
            }

            $violations = [];
            if (isset($packageData['files'])) {
                foreach ($packageData['files'] as $file) {
                    foreach ($file['violations'] as $violation) {
                        $violations[(string)$file['path']][] = $this->createViolation($violation);
                    }
                }
            }

            if ($package && $packageData['id'] === $package) {
                $packages[] = new PackageViolationReport(
                    $packageData['id'],
                    $packageData['path'],
                    $packageViolations,
                    $violations,
                );

                break;
            }

            $packages[] = new PackageViolationReport(
                $packageData['id'],
                $packageData['path'],
                $packageViolations,
                $violations,
            );
        }

        $projectViolations = [];
        if (isset($violationReportData['violations'])) {
            foreach ($violationReportData['violations'] as $violation) {
                $projectViolations[] = $this->createViolation($violation);
            }
        }

        return new ViolationReport($violationReportData['project'], $violationReportData['path'], $projectViolations, $packages);
    }

    /**
     * @param \SprykerSdk\Sdk\Contracts\Violation\ViolationInterface $violation
     *
     * @return array<string, mixed>
     */
    protected function convertViolationToArray(ViolationInterface $violation): array
    {
        $violationData = [];

        $violationData['id'] = $violation->getId();
        $violationData['message'] = $violation->getMessage();
        $violationData['priority'] = $violation->priority();
        $violationData['class'] = $violation->getClass();
        $violationData['method'] = $violation->getMethod();
        $violationData['start_line'] = $violation->getStartLine();
        $violationData['end_line'] = $violation->getEndLine();
        $violationData['start_column'] = $violation->getStartColumn();
        $violationData['end_column'] = $violation->getStartColumn();
        $violationData['additional_attributes'] = $violation->getAdditionalAttributes();
        $violationData['fixable'] = $violation->isFixable();
        $violationData['produced_by'] = $violation->producedBy();

        return $violationData;
    }

    /**
     * @param array $violation
     *
     * @return \SprykerSdk\Sdk\Contracts\Violation\ViolationInterface
     */
    protected function createViolation(array $violation): ViolationInterface
    {
        return new Violation(
            $violation['id'],
            $violation['message'],
            $violation['priority'] ?: null,
            $violation['class'] ?: null,
            $violation['method'] ?: null,
            $violation['start_line'] ?: null,
            $violation['end_line'] ?: null,
            $violation['start_column'] ?: null,
            $violation['end_column'] ?: null,
            $violation['additional_attributes'],
            $violation['fixable'] ?: false,
            $violation['produced_by'] ?: '',
        );
    }

    /**
     * @param string|null $taskId
     *
     * @throws \Exception
     *
     * @return void
     */
    public function cleanupViolationReport(?string $taskId = null): void
    {
        $dirname = $this->getViolationReportPath($taskId);

        if (!$dirname) {
            return;
        }

        if (is_dir($dirname)) {
            $dir = new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST) as $object) {
                if ($object->isFile()) {
                    unlink($object);
                } elseif ($object->isDir()) {
                    rmdir($object);
                } else {
                    throw new Exception('Unknown object type: ' . $object->getFileName());
                }
            }
        }
    }

    /**
     * @param string|null $taskId
     *
     * @throws \SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException
     *
     * @return string
     */
    protected function getViolationReportPath(?string $taskId): string
    {
        $reportDirSetting = $this->projectSettingRepository->findOneByPath(static::REPORT_DIR_SETTING_NAME);

        if (!$reportDirSetting) {
            throw new MissingSettingException(sprintf('Some of setting definition for %s not found', static::REPORT_DIR_SETTING_NAME));
        }

        $reportPath = $reportDirSetting->getValues();

        if (!$taskId) {
            return $reportPath;
        }

        return $reportPath . DIRECTORY_SEPARATOR . $taskId . '.violations.yaml';
    }
}
