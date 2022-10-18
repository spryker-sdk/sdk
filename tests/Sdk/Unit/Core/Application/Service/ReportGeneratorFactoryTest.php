<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Service\Report\ReportGeneratorInterface;
use SprykerSdk\Sdk\Core\Application\Service\Report\ReportGeneratorResolverInterface;
use SprykerSdk\Sdk\Core\Application\Service\ReportGeneratorFactory;
use SprykerSdk\SdkContracts\Entity\ContextInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group ReportGeneratorFactoryTest
 * Add your own group annotations below this line
 */
class ReportGeneratorFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testReportGeneratorFactoryReturnsOnlyResolvedReportGenerators(): void
    {
        $reportGenerator = $this->createReportGeneratorMock();

        $reportGeneratorFactory = new ReportGeneratorFactory(
            [
                $this->createReportResolverMock($reportGenerator),
                $this->createReportResolverMock(null),
            ],
        );

        $reportGenerators = $reportGeneratorFactory->getReportGeneratorsByContext($this->createContextMock());

        $this->assertSame([$reportGenerator], $reportGenerators);
    }

    /**
     * @param \SprykerSdk\Sdk\Core\Application\Service\Report\ReportGeneratorInterface|null $reportGenerator
     *
     * @return \SprykerSdk\Sdk\Core\Application\Service\Report\ReportGeneratorResolverInterface
     */
    protected function createReportResolverMock(?ReportGeneratorInterface $reportGenerator): ReportGeneratorResolverInterface
    {
        $resolvedReportGenerator = $this->createMock(ReportGeneratorResolverInterface::class);

        $resolvedReportGenerator
            ->method('resolveByContext')
            ->willReturn($reportGenerator);

        return $resolvedReportGenerator;
    }

    /**
     * @return \SprykerSdk\Sdk\Core\Application\Service\Report\ReportGeneratorInterface
     */
    protected function createReportGeneratorMock(): ReportGeneratorInterface
    {
        return $this->createMock(ReportGeneratorInterface::class);
    }

    /**
     * @return \SprykerSdk\SdkContracts\Entity\ContextInterface
     */
    protected function createContextMock(): ContextInterface
    {
        return $this->createMock(ContextInterface::class);
    }
}
