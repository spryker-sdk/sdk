<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace InspectionDoc\Tests\Unit\Infrastructure\Reader;

use Codeception\Test\Unit;
use InspectionDoc\Entity\InspectionDocInterface;
use InspectionDoc\Infrastructure\Loader\InspectionDocDataLoaderInterface;
use InspectionDoc\Infrastructure\Reader\InspectionDocReader;
use SprykerSdk\Sdk\Infrastructure\Violation\Formatter\OutputViolationDecoratorInterface;

/**
 * Auto-generated group annotations
 *
 * @group Tests
 * @group Unit
 * @group Infrastructure
 * @group Reader
 * @group InspectionDocReaderTest
 * Add your own group annotations below this line
 */
class InspectionDocReaderTest extends Unit
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->outputViolationDecorator = $this->createMock(OutputViolationDecoratorInterface::class);
    }

    /**
     * @return void
     */
    public function testFindByErrorCode(): void
    {
        // Arrange
        $inspectionDocDataLoaderInterfaceMock = $this->createMock(InspectionDocDataLoaderInterface::class);
        $inspectionDocDataLoaderInterfaceMock->expects($this->once())
            ->method('getInspectionDocs')
            ->willReturn([
                'SprykerStrict.TypeHints.ParameterTypeHint' => [
                    'inspectionId' => 'SprykerStrict.TypeHints.ParameterTypeHint',
                    'link' => '/docs/sdk/dev/development-tools/sniffs/spryker-strict/type-hints/parameter-type-hint-sniff.html',
                ],
            ]);

        $yamlViolationReportFormatter = new InspectionDocReader($inspectionDocDataLoaderInterfaceMock);

        // Act
        $inspectionDoc = $yamlViolationReportFormatter->findByErrorCode('SprykerStrict.TypeHints.ParameterTypeHint');

        // Assert
        $this->assertInstanceOf(InspectionDocInterface::class, $inspectionDoc);
    }

    /**
     * @return void
     */
    public function testFindByErrorCodeCache(): void
    {
        // Arrange
        $inspectionDocDataLoaderInterfaceMock = $this->createMock(InspectionDocDataLoaderInterface::class);
        $inspectionDocDataLoaderInterfaceMock->expects($this->exactly(2))
            ->method('getInspectionDocs')
            ->willReturn([
                'SprykerStrict.TypeHints.ParameterTypeHint' => [
                    'inspectionId' => 'SprykerStrict.TypeHints.ParameterTypeHint',
                    'link' => '/docs/sdk/dev/development-tools/sniffs/spryker-strict/type-hints/parameter-type-hint-sniff.html',
                ],
            ]);

        $yamlViolationReportFormatter = new InspectionDocReader($inspectionDocDataLoaderInterfaceMock);

        // Act
        $inspectionDoc1 = $yamlViolationReportFormatter->findByErrorCode('SprykerStrict.TypeHints.ParameterTypeHint');
        $inspectionDoc2 = $yamlViolationReportFormatter->findByErrorCode('SprykerStrict.TypeHints.ParameterTypeHint');

        // Assert
        $this->assertInstanceOf(InspectionDocInterface::class, $inspectionDoc1);
        $this->assertEquals($inspectionDoc1, $inspectionDoc2);
    }

    /**
     * @dataProvider provideSettingList
     *
     * @return void
     */
    public function testFindByErrorCodeIfListEmpty(): void
    {
        // Arrange
        $inspectionDocDataLoaderInterfaceMock = $this->createMock(InspectionDocDataLoaderInterface::class);
        $inspectionDocDataLoaderInterfaceMock->expects($this->once())
            ->method('getInspectionDocs')
            ->willReturn([]);

        $yamlViolationReportFormatter = new InspectionDocReader($inspectionDocDataLoaderInterfaceMock);

        // Act
        $inspectionDoc = $yamlViolationReportFormatter->findByErrorCode('SprykerStrict.TypeHints.ParameterTypeHint1');

        // Assert
        $this->assertNull($inspectionDoc);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function provideSettingList(): array
    {
        return [
            'wrong name' => [
                'SprykerStrict.TypeHints.ParameterTypeHint' => [
                    'inspectionId' => 'SprykerStrict.TypeHints.ParameterTypeHint',
                    'link' => '/docs/sdk/dev/development-tools/sniffs/spryker-strict/type-hints/parameter-type-hint-sniff.html',
                ],
            ],
            'empty doc' => [],

        ];
    }
}
