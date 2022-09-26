<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Tests\UnitTester;
use SprykerSdk\SdkContracts\Entity\CommandInterface;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group LifecycleCommandBuilderTest
 */
class LifecycleCommandBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder
     */
    protected LifecycleCommandBuilder $builder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new LifecycleCommandBuilder();
    }

    /**
     * @return void
     */
    public function testBuildLifecycleCommandsShouldReturnArrayOfCommands(): void
    {
        // Arrange
        $data = $this->tester->createLifecycleCommandsData();

        // Act
        $result = $this->builder->buildLifecycleCommands($data);

        // Assert
        $this->assertNotEmpty($result);
        $this->assertContainsOnlyInstancesOf(CommandInterface::class, $result);

        $this->assertSame($data->getTaskData()['commands'][0]['command'], $result[0]->getCommand());
        $this->assertSame($data->getTaskData()['commands'][0]['type'], $result[0]->getType());
        $this->assertFalse($result[0]->hasStopOnError());

        $this->assertSame($data->getTaskData()['commands'][1]['command'], $result[1]->getCommand());
        $this->assertSame($data->getTaskData()['commands'][1]['type'], $result[1]->getType());
        $this->assertFalse($result[1]->hasStopOnError());
    }
}
