<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Infrastructure\Collector;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface;
use SprykerSdk\Sdk\Infrastructure\Collector\TaskYamlCollector;
use SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader;
use SprykerSdk\Sdk\Tests\UnitTester;

class TaskYamlCollectorTest extends Unit
{
    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Collector\TaskYamlCollector
     */
    protected TaskYamlCollector $taskYamlCollector;

    /**
     * @var \SprykerSdk\Sdk\Core\Application\Dependency\ManifestValidatorInterface
     */
    protected ManifestValidatorInterface $manifestValidator;

    /**
     * @var \SprykerSdk\Sdk\Infrastructure\Reader\TaskYamlReader
     */
    protected TaskYamlReader $taskYamlReader;

    /**
     * @var \SprykerSdk\Sdk\Tests\UnitTester
     */
    protected UnitTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->taskYamlReader = $this->createMock(TaskYamlReader::class);
        $this->manifestValidator = $this->createMock(ManifestValidatorInterface::class);

        $this->taskYamlCollector = new TaskYamlCollector($this->manifestValidator, $this->taskYamlReader);

        parent::setUp();
    }

    /**
     * @return void
     */
    public function testCollectAllShouldReturnCollectedTasks(): void
    {
        // Arrange
        $manifest = $this->tester->createManifestCollectionDto();

        $this->taskYamlReader
            ->expects($this->once())
            ->method('readFiles')
            ->willReturn($manifest);

        $this->manifestValidator
            ->expects($this->exactly(2))
            ->method('validate')
            ->willReturn($manifest->getTasks());

        // Act
        $collection = $this->taskYamlCollector->collectAll();

        // Assert
        $this->assertNotEmpty($collection->getTasks());
        $this->assertNotEmpty($collection->getTaskSets());
    }
}
