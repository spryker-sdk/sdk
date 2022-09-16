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
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\FileCollectionBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleCommandBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\LifecycleEventDataBuilder;
use SprykerSdk\Sdk\Infrastructure\Builder\Yaml\PlaceholderBuilder;
use SprykerSdk\Sdk\Tests\UnitTester;

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
        $taskPool = new TaskPool([new RemoveRepDirTask($this->createMock(ViolationReportRepositoryInterface::class))]);
        $lifecycleEventDataBuilder = new LifecycleEventDataBuilder(new FileCollectionBuilder(), new LifecycleCommandBuilder(), new PlaceholderBuilder($taskPool));
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
        $this->tester->assertLifecycleEventData('INITIALIZED', $taskYaml, $lifecycle->getInitializedEventData());
        $this->tester->assertLifecycleEventData('UPDATED', $taskYaml, $lifecycle->getUpdatedEventData());
        $this->tester->assertLifecycleEventData('REMOVED', $taskYaml, $lifecycle->getRemovedEventData());
    }
}
