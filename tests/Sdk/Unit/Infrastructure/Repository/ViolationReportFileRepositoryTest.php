<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Repository;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SprykerSdk\Sdk\Core\Appplication\Violation\ViolationReportFormatterInterface;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory;
use SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader;
use SprykerSdk\Sdk\Infrastructure\Repository\ViolationReportFileRepository;
use SprykerSdk\Sdk\Tests\UnitTester;

class ViolationReportFileRepositoryTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ViolationPathReader
     */
    protected ViolationPathReader $violationPathReader;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\Violation\ReportFormatterFactory
     */
    protected ReportFormatterFactory $reportFormatterFactory;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Repository\ViolationReportFileRepository
     */
    protected ViolationReportFileRepository $violationReportFileRepository;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->violationPathReader = $this->createMock(ViolationPathReader::class);
        $this->reportFormatterFactory = $this->createMock(ReportFormatterFactory::class);
        $this->violationReportFileRepository = new ViolationReportFileRepository(
            $this->violationPathReader,
            $this->reportFormatterFactory,
        );
    }

    /**
     * @return void
     */
    public function testSave(): void
    {
        // Arrange
        $taskId = 'task-id';
        $violationReport = $this->tester->createViolationReport($this->tester->createArrayViolationReport());

        $formatter = $this->createMock(ViolationReportFormatterInterface::class);
        $formatter
            ->expects($this->once())
            ->method('format')
            ->with($taskId, $violationReport);

        $this->reportFormatterFactory
            ->method('getViolationReportFormatter')
            ->willReturn($formatter);

        // Act
        $this->violationReportFileRepository->save($taskId, $violationReport);
    }

    /**
     * @return void
     */
    public function testFindByTaskWithViolationReportFormatterShouldReturnReport(): void
    {
        // Arrange
        $taskId = 'task-id';
        $violationReport = $this->tester->createViolationReport($this->tester->createArrayViolationReport());

        $formatter = $this->createMock(ViolationReportFormatterInterface::class);
        $formatter
            ->expects($this->once())
            ->method('read')
            ->with($taskId)
            ->willReturn($violationReport);

        $this->reportFormatterFactory
            ->method('getViolationReportFormatter')
            ->willReturn($formatter);

        // Act
        $result = $this->violationReportFileRepository->findByTask($taskId);

        // Assert
        $this->assertSame($violationReport, $result);
    }

    /**
     * @return void
     */
    public function testFindByTaskWithoutViolationReportFormatterShouldReturnNull(): void
    {
        // Arrange
        $taskId = 'task-id';

        $this->reportFormatterFactory
            ->method('getViolationReportFormatter')
            ->willReturn(null);

        // Act
        $result = $this->violationReportFileRepository->findByTask($taskId);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testCleanupViolationReportShouldRemoveFileAndDirs(): void
    {
        // Arrange
        $vfsStream = vfsStream::setup();

        $dir = new vfsStreamDirectory('test-dir');
        $dir->addChild($this->tester->createVfsStreamFile('file1', 'content1'));
        $dir->addChild($this->tester->createVfsStreamFile('file2', 'content2'));
        $dir->addChild($this->tester->createVfsStreamFile('file3', 'content3'));
        $dir->addChild(new vfsStreamDirectory('dir-in-dir'));
        $vfsStream->addChild($dir);

        $this->violationPathReader
            ->expects($this->once())
            ->method('getViolationReportDirPath')
            ->willReturn($vfsStream->url());

        // Act
        $this->violationReportFileRepository->cleanupViolationReport();

        // Assert
        $this->assertEmpty($vfsStream->getChildren());
    }

    /**
     * @return void
     */
    public function testCleanupViolationReportWithEmptyDirShouldNotRemoveFileAndDirs(): void
    {
        // Arrange
        $vfsStream = vfsStream::setup();

        $dir = new vfsStreamDirectory('test-dir');
        $dir->addChild($this->tester->createVfsStreamFile('file1', 'content1'));
        $dir->addChild($this->tester->createVfsStreamFile('file2', 'content2'));
        $dir->addChild($this->tester->createVfsStreamFile('file3', 'content3'));
        $dir->addChild(new vfsStreamDirectory('dir-in-dir'));
        $vfsStream->addChild($dir);

        $this->violationPathReader
            ->expects($this->once())
            ->method('getViolationReportDirPath')
            ->willReturn('');

        // Act
        $this->violationReportFileRepository->cleanupViolationReport();

        // Assert
        $this->assertNotEmpty($vfsStream->getChildren());
    }
}
