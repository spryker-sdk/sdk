<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Converter;

use SprykerSdk\Sdk\Extension\Converter\CodeceptionViolationReportConverter;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

class CodeceptionViolationReportConverterTest extends ReportConverterTest
{
    /**
     * @return void
     */
    public function testReturnNullWhenTestCasesAreEmpty(): void
    {
        //Arrange
        $this->addJsonReport([]);
        $settingRepository = $this->createSettingRepositoryMock(
            $this->createSettingMock(''),
            $this->createSettingMock($this->vfsStream->url() . DIRECTORY_SEPARATOR . static::REPORT_DIR),
        );

        $converter = new CodeceptionViolationReportConverter($settingRepository);

        //Act
        $converter->configure(['input_file' => 'report.json', 'producer' => 'producer']);
        $violationReport = $converter->convert();

        //Assert
        $this->assertNull($violationReport);
    }

    /**
     * @return void
     */
    public function testReturnViolationReportWhenReportIsSet(): void
    {
        //Arrange
        $this->addReport(
            json_encode([['event' => 'test', 'status' => 'fail', 'message' => 'some message', 'trace' => [['line' => 1], ['function' => 'testFunction']]]]) .
            "\n" .
            json_encode([['event' => 'test', 'status' => 'success']]),
        );
        $settingRepository = $this->createSettingRepositoryMock(
            $this->createSettingMock(''),
            $this->createSettingMock($this->vfsStream->url() . DIRECTORY_SEPARATOR . static::REPORT_DIR),
        );

        $converter = new CodeceptionViolationReportConverter($settingRepository);

        //Act
        $converter->configure(['input_file' => 'report.json', 'producer' => 'producer']);
        $violationReport = $converter->convert();

        //Assert
        $this->assertInstanceOf(ViolationReportInterface::class, $violationReport);
        $this->assertCount(1, $violationReport->getPackages());
    }

    /**
     * @return string
     */
    protected function getConverterClass(): string
    {
        return CodeceptionViolationReportConverter::class;
    }
}
