<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory;
use SprykerSdk\Sdk\Infrastructure\Registry\TaskRegistry;
use SprykerSdk\Sdk\Infrastructure\Validator\NestedTaskSetValidator;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group LifecycleEventDataBuilderTest
 */
class LifecycleEventDataBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder
     */
    protected LifecycleEventDataBuilder $lifecycleEventDataBuilder;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $taskRegistry = new TaskRegistry([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]);
        $this->lifecycleEventDataBuilder = new LifecycleEventDataBuilder(
            new FileCollectionBuilder(),
            new LifecycleCommandBuilder(),
            new PlaceholderBuilder($taskRegistry, new NestedTaskSetValidator(), new PlaceholderFactory()),
        );
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBuildInitializedEventData(): void
    {
        // Arrange
        $taskYaml = $this->tester->createLifecycleEventData();

        // Act
        $eventData = $this->lifecycleEventDataBuilder->buildInitializedEventData($taskYaml);

        // Assert
        $this->tester->assertLifecycleEventData('INITIALIZED', $taskYaml, $eventData);
    }

    /**
     * @return void
     */
    public function testBuildRemovedEventData(): void
    {
        // Arrange
        $taskYaml = $this->tester->createLifecycleEventData();

        // Act
        $eventData = $this->lifecycleEventDataBuilder->buildRemovedEventData($taskYaml);

        // Assert
        $this->tester->assertLifecycleEventData('REMOVED', $taskYaml, $eventData);
    }

    /**
     * @return void
     */
    public function testBuildUpdatedEventData(): void
    {
        // Arrange
        $taskYaml = $this->tester->createLifecycleEventData();

        // Act
        $eventData = $this->lifecycleEventDataBuilder->buildUpdatedEventData($taskYaml);

        // Assert
        $this->tester->assertLifecycleEventData('UPDATED', $taskYaml, $eventData);
    }
}
