<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Builder\Yaml;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ViolationReportRepositoryInterface;
use SprykerSdk\Sdk\Core\Application\Service\TaskRegistry;
use SprykerSdk\Sdk\Core\Application\TaskValidator\NestedTaskSetValidator;
use SprykerSdk\Sdk\Core\Domain\Enum\LifecycleName;
use SprykerSdk\Sdk\Extension\Task\RemoveRepDirTask;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Infrastructure\Factory\PlaceholderFactory;
use SprykerSdk\Sdk\Tests\UnitTester;

/**
 * @group Sdk
 * @group Unit
 * @group Infrastructure
 * @group Builder
 * @group Yaml
 * @group LifecycleBuilderTest
 */
class LifecycleBuilderTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder
     */
    protected LifecycleBuilder $lifecycleBuilder;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $taskRegistry = new TaskRegistry([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]);
        $lifecycleEventDataBuilder = new LifecycleEventDataBuilder(new FileCollectionBuilder(), new LifecycleCommandBuilder(), new PlaceholderBuilder($taskRegistry, new NestedTaskSetValidator(), new PlaceholderFactory()));
        $this->lifecycleBuilder = new LifecycleBuilder($lifecycleEventDataBuilder);
        parent::setUp();
    }

    /**
     * @return void
     */
    public function test(): void
    {
        // Arrange
        $taskYaml = $this->tester->createLifecycleEventData();

        // Act
        $lifecycle = $this->lifecycleBuilder->buildLifecycle($taskYaml);

        // Assert
        $this->tester->assertLifecycleEventData(LifecycleName::INITIALIZED, $taskYaml, $lifecycle->getInitializedEventData());
        $this->tester->assertLifecycleEventData(LifecycleName::UPDATED, $taskYaml, $lifecycle->getUpdatedEventData());
        $this->tester->assertLifecycleEventData(LifecycleName::REMOVED, $taskYaml, $lifecycle->getRemovedEventData());
    }
}
