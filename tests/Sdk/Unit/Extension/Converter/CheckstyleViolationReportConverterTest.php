<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Converter;

use SprykerSdk\Sdk\Extension\Converter\CheckstyleViolationReportConverter;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group Converter
 * @group CheckstyleViolationReportConverterTest
 * Add your own group annotations below this line
 */
class CheckstyleViolationReportConverterTest extends ReportConverterTest
{
    /**
     * @return void
     */
    public function testReturnNullWhenReportFilesFieldIsEmpty(): void
    {
        //Arrange
        $this->addJsonReport([]);
        $settingRepository = $this->createSettingRepositoryMock(
            $this->createSettingMock(''),
            $this->createSettingMock($this->vfsStream->url() . DIRECTORY_SEPARATOR . static::REPORT_DIR),
        );
        $converter = new CheckstyleViolationReportConverter($settingRepository);

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
        $this->addJsonReport([
        'files' => [
            'test/path/one' => ['errors' => 1, 'messages' => ['message' => ['message' => 'some err', 'line' => 1, 'column' => 1, 'fixable' => true]]],
            'test/path/two' => ['errors' => 0],
        ]]);
        $settingRepository = $this->createSettingRepositoryMock(
            $this->createSettingMock('project/path'),
            $this->createSettingMock($this->vfsStream->url() . DIRECTORY_SEPARATOR . static::REPORT_DIR),
        );

        $converter = new CheckstyleViolationReportConverter($settingRepository);
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
        return CheckstyleViolationReportConverter::class;
    }
}
