<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Tests\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger;
use SprykerSdk\Sdk\Core\Domain\Entity\Converter;
use SprykerSdk\Sdk\Core\Domain\Entity\Violation\ViolationReport;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Violation\ViolationConverterInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportableInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

class ViolationReportGeneratorTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger
     */
    protected ViolationReportMerger $violationReportMerger;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface
     */
    protected ViolationReportRepositoryInterface $violationReportRepository;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver
     */
    protected ViolationConverterResolver $violationConverterResolver;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator
     */
    protected ViolationReportGenerator $violationReportGenerator;

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

        $this->violationConverterResolver = $this->createMock(ViolationConverterResolver::class);
        $this->violationReportMerger = $this->createMock(ViolationReportMerger::class);
        $this->violationReportRepository = $this->createMock(ViolationReportRepositoryInterface::class);

        $this->violationReportGenerator = new ViolationReportGenerator(
            $this->violationReportMerger,
            $this->violationReportRepository,
            $this->violationConverterResolver,
        );
    }

    /**
     * @return void
     */
    public function testCollectViolationsWithEmptyCommandsShouldReturnNull(): void
    {
        // Arrange
        $taskId = 'hello:world';
        $commands = [];

        // Act
        $result = $this->violationReportGenerator->collectViolations($taskId, $commands);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testCollectViolations(): void
    {
        // Arrange
        $taskId = 'hello:world';

        $converter = new Converter('converter', []);
        $report = new ViolationReport('project', '/foo/path');

        $violationReportableCommand = new class () implements ViolationReportableInterface
        {
            /**
             * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface|null
             */
            public function getViolationReport(): ?ViolationReportInterface
            {
                return new ViolationReport('project', '/foo/path');
            }
        };

        $commands = [
            $this->tester->createCommand($converter),
            $this->tester->createCommand(clone $converter),
            $this->tester->createCommand(clone $converter),
        ];

        $violationConverterMock = $this->createMock(ViolationConverterInterface::class);
        $violationConverterMock
            ->expects($this->exactly(count($commands)))
            ->method('convert')
            ->willReturn($report);

        $this->violationConverterResolver
            ->expects($this->exactly(count($commands)))
            ->method('resolve')
            ->willReturn($violationConverterMock);

        $this->violationReportMerger
            ->expects($this->once())
            ->method('merge')
            ->willReturn($report);

        $this->violationReportRepository
            ->expects($this->once())
            ->method('save')
            ->with($taskId, $report);

        $commands[] = $violationReportableCommand;

        // Act
        $result = $this->violationReportGenerator->collectViolations($taskId, $commands);

        // Assert
        $this->assertSame($report, $result);
    }
}
