<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistry;
use SprykerSdk\Sdk\Infrastructure\Validator\NestedTaskSetValidator;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group PlaceholderBuilderTest
 */
class PlaceholderBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder
     */
    protected PlaceholderBuilder $placeholderBuilder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->placeholderBuilder = new PlaceholderBuilder(
            new TaskRegistry([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]),
            new NestedTaskSetValidator(),
            new PlaceholderFactory(),
        );
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildPlaceholdersShouldReturnPlaceholders(): void
    {
        // Arrange
        $taskYaml = $this->tester->createPlaceholdersData();
        $taskYamlDataPlaceholders = $taskYaml->getTaskData()['placeholders'];
        $firstPlaceholderName = $taskYamlDataPlaceholders[0]['name'];
        $secondPlaceholderName = $taskYamlDataPlaceholders[1]['name'];

        // Act
        $placeholders = $this->placeholderBuilder->buildPlaceholders($taskYaml);

        // Assert
        $this->assertCount(count($taskYamlDataPlaceholders), $placeholders);
        $this->assertContainsOnlyInstancesOf(PlaceholderInterface::class, $placeholders);

        $this->assertSame($taskYamlDataPlaceholders[0]['configuration'], $placeholders[$firstPlaceholderName]->getConfiguration());
        $this->assertSame($taskYamlDataPlaceholders[0]['name'], $placeholders[$firstPlaceholderName]->getName());
        $this->assertSame($taskYamlDataPlaceholders[0]['value_resolver'], $placeholders[$firstPlaceholderName]->getValueResolver());
        $this->assertSame($taskYamlDataPlaceholders[1]['configuration'], $placeholders[$secondPlaceholderName]->getConfiguration());
        $this->assertSame($taskYamlDataPlaceholders[1]['name'], $placeholders[$secondPlaceholderName]->getName());
        $this->assertSame($taskYamlDataPlaceholders[1]['value_resolver'], $placeholders[$secondPlaceholderName]->getValueResolver());
    }
}
