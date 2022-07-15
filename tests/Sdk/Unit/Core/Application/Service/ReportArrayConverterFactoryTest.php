<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException;
use SprykerSdk\Sdk\Core\Appplication\Service\ReportArrayConverterFactory;
use SprykerSdk\SdkContracts\Report\ReportArrayConverterInterface;
use SprykerSdk\SdkContracts\Report\ReportInterface;
use stdClass;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group ReportArrayConverterFactoryTest
 */
class ReportArrayConverterFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testThrowsExceptionWhenSupportedTypeNotFound(): void
    {
        //Arrange
        $reportArrayConverter = $this->createReportArrayConverterMock('testType', '');
        $reportArrayConverterFactory = new ReportArrayConverterFactory([$reportArrayConverter]);

        //Act
        $this->expectException(InvalidReportTypeException::class);
        $reportArrayConverterFactory->getArrayConverterByType('InvalidType');
    }

    /**
     * @return void
     */
    public function testReturnsValidReportArrayConverterWhenSupportedTypeFound(): void
    {
        //Arrange
        $reportArrayConverter = $this->createReportArrayConverterMock('testType', '');
        $reportArrayConverterFactory = new ReportArrayConverterFactory([$reportArrayConverter]);

        //Act
        $foundReportArrayConverter = $reportArrayConverterFactory->getArrayConverterByType('testType');

        //Assert
        $this->assertSame($reportArrayConverter, $foundReportArrayConverter);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenSupportedClassNotFound(): void
    {
        //Arrange
        $reportArrayConverter = $this->createReportArrayConverterMock('', stdClass::class);
        $reportArrayConverterFactory = new ReportArrayConverterFactory([$reportArrayConverter]);
        $report = $this->createReportMock();

        //Act
        $this->expectException(InvalidReportTypeException::class);
        $reportArrayConverterFactory->getArrayConverterByReport($report);
    }

    /**
     * @return void
     */
    public function testReturnsValidReportArrayConverterWhenSupportedClassFound(): void
    {
        //Arrange
        $reportArrayConverter = $this->createReportArrayConverterMock('testType', ReportInterface::class);
        $reportArrayConverterFactory = new ReportArrayConverterFactory([$reportArrayConverter]);
        $report = $this->createReportMock();

        //Act
        $foundReportArrayConverter = $reportArrayConverterFactory->getArrayConverterByReport($report);

        //Assert
        $this->assertSame($reportArrayConverter, $foundReportArrayConverter);
    }

    /**
     * @param string $supportedType
     * @param string $supportedClass
     *
     * @return \SprykerSdk\SdkContracts\Report\ReportArrayConverterInterface
     */
    protected function createReportArrayConverterMock(string $supportedType, string $supportedClass): ReportArrayConverterInterface
    {
        $reportArrayConverterMock = $this->createMock(ReportArrayConverterInterface::class);
        $reportArrayConverterMock->method('getSupportedReportType')->willReturn($supportedType);
        $reportArrayConverterMock->method('getSupportedReportClass')->willReturn($supportedClass);

        return $reportArrayConverterMock;
    }

    /**
     * @return \SprykerSdk\SdkContracts\Report\ReportInterface
     */
    protected function createReportMock(): ReportInterface
    {
        return $this->createMock(ReportInterface::class);
    }
}
