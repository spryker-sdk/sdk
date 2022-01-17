<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportGenerator;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger;
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
        $violationReportGenerator = new ViolationReportGenerator(
            $this->createViolationReportMergerMock(),
            $this->createViolationReportRepositoryMock(),
            $this->createViolationConverterResolverMock(),
        );

        $violationReport = $violationReportGenerator->collectViolations(
            'test',
            [
                $this->createViolationReportableMock(),
                $this->createCommandMock(),
            ],
        );

        $this->assertNotNull($violationReport);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Violation\ViolationReportableInterface
     */
    protected function createViolationReportableMock(): ViolationReportableInterface
    {
        $violationReportable = $this->createMock(ViolationReportableInterface::class);
        $violationReportable->expects($this->once())
            ->method('getViolationReport')
            ->willReturn($this->createViolationReportMock());

        return $violationReportable;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportMerger
     */
    protected function createViolationReportMergerMock(): ViolationReportMerger
    {
        return $this->createMock(ViolationReportMerger::class);
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationConverterResolver
     */
    protected function createViolationConverterResolverMock(): ViolationConverterResolver
    {
        $violationConverter = $this->createMock(ViolationConverterResolver::class);
        $violationConverter->expects($this->once())
            ->method('resolve');

        return $violationConverter;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Appplication\Dependency\ViolationReportRepositoryInterface
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
