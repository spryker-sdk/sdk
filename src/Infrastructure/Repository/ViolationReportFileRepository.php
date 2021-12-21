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
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Yaml\Yaml;

class ViolationReportFileRepository implements ViolationReportRepositoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader
     */
    protected ViolationPathReader $violationPathReader;

    protected ReportFormatterFactory $reportFormatterFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader $violationPathReader
     * @param \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory $reportFormatterFactory
     */
    public function __construct(
        ViolationPathReader $violationPathReader,
        ReportFormatterFactory $reportFormatterFactory
    ) {
        $this->violationPathReader = $violationPathReader;
        $this->reportFormatterFactory = $reportFormatterFactory;
    }

    /**
     * @param string $taskId
     * @param \SprykerSdk\SdkContracts\Violation\ViolationReportInterface $violationReport
     *
     * @return void
     */
    public function save(string $taskId, ViolationReportInterface $violationReport): void
    {
        $this->reportFormatterFactory->getViolationReportFormatter()?->format($taskId, $violationReport);
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface
    {
        return $this->reportFormatterFactory->getViolationReportFormatter()?->read($taskId);
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function cleanupViolationReport(): void
    {
        $dirname = $this->violationPathReader->getViolationReportDirPath();

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
}
