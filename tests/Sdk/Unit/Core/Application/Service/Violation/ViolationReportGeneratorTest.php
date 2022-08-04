<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\ConverterResolver;
use SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportGenerator;
use SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportMerger;
use SprykerSdk\SdkContracts\Entity\CommandInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportableInterface;
use SprykerSdk\SdkContracts\Violation\ViolationReportInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group Violation
 * @group ViolationReportGeneratorTest
 */
class ViolationReportGeneratorTest extends Unit
{
    /**
     * @return void
     */
    public function testCollectViolations(): void
    {
        // Arrange
        $violationReportGenerator = new ViolationReportGenerator(
            $this->createViolationReportMergerMock(),
            $this->createViolationReportRepositoryMock(),
            $this->createViolationConverterResolverMock(),
        );

        // Act
        $violationReport = $violationReportGenerator->collectReports(
            'test',
            [
                $this->createViolationReportableMock(),
                $this->createCommandMock(),
            ],
        );

        // Assert
        $this->assertNotNull($violationReport);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportableInterface
     */
    protected function createViolationReportableMock(): ViolationReportableInterface
    {
        $violationReportable = $this->createMock(ViolationReportableInterface::class);
        $violationReportable->expects($this->once())
            ->method('getReport')
            ->willReturn($this->createViolationReportMock());

        return $violationReportable;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportMerger
     */
    protected function createViolationReportMergerMock(): ViolationReportMerger
    {
        return $this->createMock(ViolationReportMerger::class);
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Service\ConverterResolver
     */
    protected function createViolationConverterResolverMock(): ConverterResolver
    {
        $violationConverter = $this->createMock(ConverterResolver::class);
        $violationConverter->expects($this->once())
            ->method('resolve');

        return $violationConverter;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface
     */
    protected function createViolationReportRepositoryMock(): ViolationReportRepositoryInterface
    {
        $violationReportRepository = $this->createMock(ViolationReportRepositoryInterface::class);
        $violationReportRepository->expects($this->once())
            ->method('save');

        return $violationReportRepository;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportInterface
     */
    protected function createViolationReportMock(): ViolationReportInterface
    {
        return $this->createMock(ViolationReportInterface::class);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\CommandInterface
     */
    protected function createCommandMock(): CommandInterface
    {
        return $this->createMock(CommandInterface::class);
    }
}
