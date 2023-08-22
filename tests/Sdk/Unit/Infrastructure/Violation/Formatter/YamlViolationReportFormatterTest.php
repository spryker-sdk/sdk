<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Violation\Formatter;

use Codeception\Test\Unit;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\YamlViolationReportFormatter;
use SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader;
use SprykerSdk\SdkContracts\Report\Violation\ViolationReportInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Infrastructure
 * @group Violation
 * @group Formatter
 * @group YamlViolationReportFormatterTest
 * Add your own group annotations below this line
 */
class YamlViolationReportFormatterTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Mapper\ViolationReportFileMapperInterface&\PHPUnit\Framework\MockObject\MockObject
     */
    protected ViolationReportFileMapperInterface $violationReportFileMapper;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\ViolationPathReader&\PHPUnit\Framework\MockObject\MockObject
     */
    protected ViolationPathReader $violationPathReader;

    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected Yaml $yamlParser;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Violation\Formatter\ViolationReportDecorator&\PHPUnit\Framework\MockObject\MockObject
     */
    protected ViolationReportDecorator $violationReportDecorator;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected vfsStreamDirectory $vfsStream;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->violationReportFileMapper = $this->createMock(ViolationReportFileMapperInterface::class);
        $this->violationPathReader = $this->createMock(ViolationPathReader::class);
        $this->yamlParser = new Yaml();
        $this->violationReportDecorator = $this->createMock(ViolationReportDecorator::class);

        $this->vfsStream = vfsStream::setup();
    }

    /**
     * @return void
     */
    public function testRead(): void
    {
        // Arrange
        $vfsFile = new vfsStreamFile('test.yaml');
        $vfsFile->setContent('project: test');

        $this->vfsStream->addChild($vfsFile);

        $this->violationPathReader
            ->method('getViolationReportPath')
            ->with('name')
            ->willReturn($vfsFile->url());

        $violationReportMock = $this->createMock(ViolationReportInterface::class);
        $this->violationReportFileMapper
            ->method('mapFileStructureToViolationReport')
            ->willReturn($violationReportMock);

        $yamlViolationReportFormatter = new YamlViolationReportFormatter(
            $this->violationReportFileMapper,
            $this->violationPathReader,
            $this->yamlParser,
            $this->violationReportDecorator,
        );

        // Act
        $violationReport = $yamlViolationReportFormatter->read('name');

        // Assert
        $this->assertSame($violationReportMock, $violationReport);
    }

    /**
     * @return void
     */
    public function testFormat(): void
    {
        // Arrange
        $vfsFile = new vfsStreamFile('test.yaml');

        $vfsDir = new vfsStreamDirectory('report');
        $vfsDir->addChild($vfsFile);

        $this->vfsStream->addChild($vfsDir);

        $this->violationPathReader
            ->method('getViolationReportDirPath')
            ->willReturn('report');
        $this->violationPathReader->method('getViolationReportPath')
            ->with('test')
            ->willReturn($vfsFile->url());
        $this->violationReportFileMapper
            ->method('mapViolationReportToYamlStructure')
            ->willReturn(['project' => 'test']);

        $violationReportMock = $this->createMock(ViolationReportInterface::class);

        $yamlViolationReportFormatter = new YamlViolationReportFormatter(
            $this->violationReportFileMapper,
            $this->violationPathReader,
            $this->yamlParser,
            $this->violationReportDecorator,
        );

        // Act
        $yamlViolationReportFormatter->format('test', $violationReportMock);

        // Assert
        $this->assertSame("project: test\n", $vfsFile->getContent());
    }
}
