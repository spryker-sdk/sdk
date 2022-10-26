<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Unit\Core\Application\Service\Violation;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Exception\InvalidReportTypeException;
use SprykerSdk\Sdk\Core\Application\Exception\MissingValueException;
use SprykerSdk\Sdk\Core\Application\Service\Violation\ViolationReportArrayConverter;
use SprykerSdk\SdkContracts\Report\ReportInterface;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Core
 * @group Application
 * @group Service
 * @group Violation
 * @group ViolationReportArrayConverterTest
 * Add your own group annotations below this line
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
             * @return array<\SprykerSdk\SdkContracts\Report\Violation\PackageViolationReportInterface>
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
             * @return array<\SprykerSdk\SdkContracts\Report\Violation\ViolationInterface>
             */
            public function getViolations(): array
            {
                return [];
            }
        };
    }
}
