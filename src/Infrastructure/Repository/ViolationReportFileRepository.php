<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use Exception;
use RecursiveIteratorIterator;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Infrastructure\Violation\ReportFormatterFactory;
use SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader;
use SprykerSdk\SdkContracts\Report\ReportInterface;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class ViolationReportFileRepository implements ViolationReportRepositoryInterface
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader
     */
    protected ViolationPathReader $violationPathReader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\ReportFormatterFactory
     */
    protected ReportFormatterFactory $reportFormatterFactory;

    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader $violationPathReader
     * @param \SprykerSdk\Sdk\Infrastructure\Violation\ReportFormatterFactory $reportFormatterFactory
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
     * @param \SprykerSdk\SdkContracts\Report\ReportInterface $violationReport
     *
     * @throws \Symfony\Component\Translation\Exception\InvalidResourceException
     *
     * @return void
     */
    public function save(string $taskId, ReportInterface $violationReport): void
    {
        if (!($violationReport instanceof ViolationReportInterface)) {
            throw new InvalidResourceException(sprintf('Invalid report type "%s"', get_class($violationReport)));
        }

        if ($this->reportFormatterFactory->getViolationReportFormatter()) {
            $this->reportFormatterFactory->getViolationReportFormatter()->format($taskId, $violationReport);
        }
    }

    /**
     * @param string $taskId
     *
     * @return \SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface|null
     */
    public function findByTask(string $taskId): ?ViolationReportInterface
    {
        if (!$this->reportFormatterFactory->getViolationReportFormatter()) {
            return null;
        }

        return $this->reportFormatterFactory->getViolationReportFormatter()->read($taskId);
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function cleanUp(): void
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
