<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Extension\Converter;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\SdkContracts\Entity\SettingInterface;
use SprykerSdk\SdkContracts\Enum\Setting;

/**
 * Auto-generated group annotations
 *
 * @group Sdk
 * @group Unit
 * @group Extension
 * @group Converter
 * @group ReportConverterTest
 * Add your own group annotations below this line
 */
abstract class ReportConverterTest extends Unit
{
    /**
     * @var string
     */
    protected const REPORT_DIR = 'reports';

    protected vfsStreamDirectory $vfsStream;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->vfsStream = vfsStream::setup();
    }

    /**
     * @return void
     */
    public function testReturnNullWhenProjectDirNotFound(): void
    {
        //Arrange
        $settingRepository = $this->createSettingRepositoryMock(null);
        $converterClass = $this->getConverterClass();
        $converter = new $converterClass($settingRepository);

        //Act
        $converter->configure(['input_file' => 'report.json', 'producer' => 'producer']);
        $violationReport = $converter->convert();

        //Assert
        $this->assertNull($violationReport);
    }

    /**
     * @return void
     */
    public function testReturnNullWhenCantReadFile(): void
    {
        //Arrange
        $this->addJsonReport([]);
        $settingRepository = $this->createSettingRepositoryMock($this->createSettingMock(''), $this->createSettingMock(static::REPORT_DIR));
        $converterClass = $this->getConverterClass();
        $converter = new $converterClass($settingRepository);

        //Act
        $converter->configure(['input_file' => 'report.json', 'producer' => 'producer']);
        $violationReport = $converter->convert();

        //Assert
        $this->assertNull($violationReport);
    }

    /**
     * @param string $value
     *
     * @return \SprykerSdk\SdkContracts\Entity\SettingInterface
     */
    protected function createSettingMock(string $value): SettingInterface
    {
        $settingMock = $this->createMock(SettingInterface::class);
        $settingMock->method('getValues')->willReturn($value);

        return $settingMock;
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface|null $projectDirectory
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface|null $reportDirectory
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dependency\Repository\SettingRepositoryInterface
     */
    protected function createSettingRepositoryMock(
        ?SettingInterface $projectDirectory = null,
        ?SettingInterface $reportDirectory = null
    ): SettingRepositoryInterface {
        $settingRepositoryMock = $this->createMock(SettingRepositoryInterface::class);

        $settingRepositoryMock
            ->method('findOneByPath')
            ->willReturnMap([
                    [Setting::PATH_PROJECT_DIR, $projectDirectory],
                    [Setting::PATH_REPORT_DIR, $reportDirectory],
                ]);

        return $settingRepositoryMock;
    }

    /**
     * @param array $reportContent
     * @param string $fileName
     *
     * @return void
     */
    protected function addJsonReport(array $reportContent, string $fileName = 'report.json'): void
    {
        $this->addReport(json_encode($reportContent, JSON_THROW_ON_ERROR));
    }

    /**
     * @param string $reportContent
     * @param string $fileName
     *
     * @return void
     */
    protected function addReport(string $reportContent, string $fileName = 'report.json'): void
    {
        $vfsFile = new vfsStreamFile($fileName);
        $vfsFile->setContent($reportContent);

        $vfsDir = new vfsStreamDirectory(static::REPORT_DIR);
        $vfsDir->addChild($vfsFile);

        $this->vfsStream->addChild($vfsDir);
    }

    /**
     * @return string
     */
    abstract protected function getConverterClass(): string;
}
