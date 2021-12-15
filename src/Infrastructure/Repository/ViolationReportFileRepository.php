<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Exception;
use RecursiveIteratorIterator;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;
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
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapper;

    /**
     * @param \SprykerSdk\Sdk\Core\Appplication\Dependency\ProjectSettingRepositoryInterface $projectSettingRepository
     * @param \Symfony\Component\Yaml\Yaml $yamlParser
     * @param \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface $violationReportFileMapper
     */
    public function __construct(
        ProjectSettingRepositoryInterface $projectSettingRepository,
        Yaml $yamlParser,
        ViolationReportFileMapperInterface $violationReportFileMapper
    ) {
        $this->projectSettingRepository = $projectSettingRepository;
        $this->yamlParser = $yamlParser;
        $this->violationReportFileMapper = $violationReportFileMapper;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ViolationReportInterface $violationReport): void
    {
        $violationReportStructure = $this->violationReportFileMapper->mapViolationReportToFileStructure($violationReport);

        file_put_contents($this->getViolationReportPath($taskId), $this->yamlParser->dump($violationReportStructure));
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface
    {
        $violationReportData = $this->yamlParser->parseFile($this->getViolationReportPath($taskId));

        return $this->violationReportFileMapper->mapFileStructureToViolationReport($violationReportData);
    }

    /**
     * @param string $taskId
     * @param array<string> $packageIds
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function findByPackage(string $taskId, array $packageIds): ?ViolationReportInterface
    {
        $violationReportData = $this->yamlParser->parseFile($this->getViolationReportPath($taskId));

        return $this->violationReportFileMapper->mapFileStructureToViolationReport($violationReportData, $packageIds);
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
