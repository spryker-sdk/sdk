<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Appplication\Exception\InvalidReportTypeException;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingValueException;
use SprykerSdk\Sdk\Core\Appplication\Service\Violation\ViolationReportArrayConverter;
use SprykerSdk\SdkContracts\Report\ReportInterface;

/**
 * @group Sdk
 * @group Core
 * @group Application
 * @group Service
 * @group Violation
 * @group ViolationReportArrayConverterTest
 */
class ViolationReportArrayConverterTest extends Unit
{
    /**
     * @return void
     */
    public function testThrowsExceptionWhenNotViolationTypeIsPassed(): void
    {
        //Arrange
        $violationReportArrayConverter = new ViolationReportArrayConverter();
        $violationReport = $this->createInvalidReportType();

        //Act
        $this->expectException(InvalidArgumentException::class);
        $violationReportArrayConverter->toArray($violationReport);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenTypeIsNotSet(): void
    {
        //Arrange
        $violationReportArrayConverter = new ViolationReportArrayConverter();
        $arrayData = $this->createArrayData();
        unset($arrayData['type']);

        //Act
        $this->expectException(InvalidReportTypeException::class);
        $violationReportArrayConverter->fromArray($arrayData);
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenTypeIsInvalid(): void
    {
        //Arrange
        $violationReportArrayConverter = new ViolationReportArrayConverter();
        $arrayData = $this->createArrayData();
        $arrayData['type'] = 'some_type';

        //Act
        $this->expectException(InvalidReportTypeException::class);
        $violationReportArrayConverter->fromArray($arrayData);
    }

    /**
     * @dataProvider requiredKeysDataProvider
     *
     * @param string $requiredKey
     *
     * @return void
     */
    public function testThrowsExceptionWhenKeyNotSetInDataArray(string $requiredKey): void
    {
        //Arrange
        $violationReportArrayConverter = new ViolationReportArrayConverter();
        $arrayData = $this->createArrayData();
        unset($arrayData[$requiredKey]);

        //Act
        $this->expectException(MissingValueException::class);
        $violationReportArrayConverter->fromArray($arrayData);
    }

    /**
     * @return array<array<string>>
     */
    public function requiredKeysDataProvider(): array
    {
        return [
            ['path'],
            ['project'],
            ['violations'],
            ['packages'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function createArrayData(): array
    {
        return [
            'type' => ViolationReportArrayConverter::VIOLATION_REPORT_TYPE,
            'path' => '',
            'project' => '',
            'violations' => [],
            'packages' => [],
        ];
    }

    /**
     * @return \SprykerSdk\SdkContracts\Report\ReportInterface
     */
    protected function createInvalidReportType(): ReportInterface
    {
        return new class implements ReportInterface
        {
            /**
             * @return string
             */
            public function getProject(): string
            {
                return '';
            }

            /**
             * @return array<\SprykerSdk\SdkContracts\Violation\PackageViolationReportInterface>
             */
            public function getPackages(): array
            {
                return [];
            }

            /**
             * @return string
             */
            public function getPath(): string
            {
                return '';
            }

            /**
             * @return array<\SprykerSdk\SdkContracts\Violation\ViolationInterface>
             */
            public function getViolations(): array
            {
                return [];
            }
        };
    }
}
