<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Presentation\Ide\PhpStorm\Service;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface;
use SprykerSdk\Sdk\Core\Appplication\Exception\MissingSettingException;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatterInterface;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoaderInterface;
use SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\ConfigManager;
use SprykerSdk\Sdk\Tests\UnitTester;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class ConfigManagerTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\ConfigManager
     */
    protected ConfigManager $configManager;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Formatter\CommandXmlFormatterInterface
     */
    protected CommandXmlFormatterInterface $commandXmlFormatter;

    /**
     * @var \Symfony\Component\Serializer\Encoder\XmlEncoder
     */
    protected XmlEncoder $xmlEncoder;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Ide\PhpStorm\Service\CommandLoaderInterface
     */
    protected CommandLoaderInterface $commandLoader;

    /**
     * @var \SprykerSdk\Sdk\Core\Appplication\Dependency\Repository\SettingRepositoryInterface
     */
    protected SettingRepositoryInterface $settingRepository;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var string
     */
    protected const TEST_PROJECT_PATH = __DIR__ . '/../../../../../../_output/project_fs';

    /**
     * @var string
     */
    protected const CONFIG_FILE_PATH = self::TEST_PROJECT_PATH . '/.idea/commandlinetools/Custom_Spryker_Sdk.xml';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->commandLoader = $this->createMock(CommandLoaderInterface::class);
        $this->commandXmlFormatter = $this->createMock(CommandXmlFormatterInterface::class);
        $this->xmlEncoder = $this->createMock(XmlEncoder::class);
        $this->settingRepository = $this->createMock(SettingRepositoryInterface::class);
    }

    /**
     * @return void
     */
    public function testCreateXmlFileShouldCreateConfigSuccessfully(): void
    {
        // Arrange
        $this->configManager = new ConfigManager(
            $this->commandLoader,
            $this->commandXmlFormatter,
            $this->xmlEncoder,
            $this->settingRepository,
            static::TEST_PROJECT_PATH,
        );
        $executableFilePath = static::TEST_PROJECT_PATH;
        $xml = <<<XML
<?xml version="1.0"?>
<framework xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="schemas/frameworkDescriptionVersion1.1.3.xsd" frameworkId="spryker-sdk" name="Spryker Sdk" invoke="$executableFilePath" alias="spryker-sdk" enabled="true" version="2">
</framework>
XML;

        $this->xmlEncoder
            ->expects($this->once())
            ->method('encode')
            ->willReturn($xml);

        $this->settingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with('project_dir')
            ->willReturn($this->tester->createSetting('project_dir', static::TEST_PROJECT_PATH));

        $ideCommands = [
            $this->tester->createPhpStormCommand('name1', [], [], 'help'),
        ];

        $this->commandLoader
            ->expects($this->once())
            ->method('load')
            ->willReturn($ideCommands);

        // Act
        $result = $this->configManager->createXmlFile();

        // Assert
        $this->assertFalse($result);
        $this->assertFileExists(static::CONFIG_FILE_PATH);
        $this->assertXmlStringEqualsXmlString(file_get_contents(static::CONFIG_FILE_PATH), $xml);
    }

    /**
     * @return void
     */
    public function testCreateXmlFileWithoutProjectDirSettingShouldThrowException(): void
    {
        // Arrange
        $this->configManager = new ConfigManager(
            $this->commandLoader,
            $this->commandXmlFormatter,
            $this->xmlEncoder,
            $this->settingRepository,
            static::TEST_PROJECT_PATH,
        );
        $executableFilePath = static::TEST_PROJECT_PATH;
        $xml = <<<XML
<?xml version="1.0"?>
<framework xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="schemas/frameworkDescriptionVersion1.1.3.xsd" frameworkId="spryker-sdk" name="Spryker Sdk" invoke="$executableFilePath" alias="spryker-sdk" enabled="true" version="2">
</framework>
XML;

        $this->xmlEncoder
            ->expects($this->once())
            ->method('encode')
            ->willReturn($xml);

        $this->settingRepository
            ->expects($this->once())
            ->method('findOneByPath')
            ->with('project_dir')
            ->willReturn(null);

        $ideCommands = [
            $this->tester->createPhpStormCommand('name1', [], [], 'help'),
        ];

        $this->commandLoader
            ->expects($this->once())
            ->method('load')
            ->willReturn($ideCommands);

        $this->expectException(MissingSettingException::class);

        // Act
        $this->configManager->createXmlFile();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tester->rmdir(static::TEST_PROJECT_PATH);
    }
}
