<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder;
use SprykerSdk\Sdk\Tests\UnitTester;

class ConverterBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\ConverterBuilder
     */
    protected ConverterBuilder $converterBuilder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->converterBuilder = new ConverterBuilder();
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildConverterWithReportConverterKeyShouldReturnConverter(): void
    {
        // Arrange
        $taskYaml = $this->tester->createConverterData();

        // Act
        $converter = $this->converterBuilder->buildConverter($taskYaml);

        // Assert
        $this->assertSame($taskYaml->getTaskData()['report_converter']['name'], $converter->getName());
        $this->assertSame($taskYaml->getTaskData()['report_converter']['configuration'], $converter->getConfiguration());
    }

    /**
     * @return void
     */
    public function testBuildWithEmptyDataShouldReturnNull(): void
    {
        // Arrange
        $taskYaml = new TaskYaml([], []);

        // Act
        $converter = $this->converterBuilder->buildConverter($taskYaml);

        // Assert
        $this->assertNull($converter);
    }
}
