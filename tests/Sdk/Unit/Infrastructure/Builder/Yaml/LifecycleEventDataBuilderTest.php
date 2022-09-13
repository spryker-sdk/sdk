<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\TaskPool;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Tests\UnitTester;

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
        $taskPool = new TaskPool([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]);
        $this->lifecycleEventDataBuilder = new LifecycleEventDataBuilder(new FileBuilder(), new LifecycleCommandBuilder(), new PlaceholderBuilder($taskPool));
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
