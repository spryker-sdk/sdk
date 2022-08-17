<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Unit\Infrastructure\Cache;

use Codeception\Test\Unit;
use SprykerSdk\Sdk\Core\Domain\Entity\Context;
use SprykerSdk\Sdk\Infrastructure\Cache\InMemoryContextCacheStorage;

/**
 * @group Sdk
 * @group Infrastructure
 * @group Cache
 * @group InMemoryContextCacheStorageTest
 */
class InMemoryContextCacheStorageTest extends Unit
{
    /**
     * @return void
     */
    public function testGetSet(): void
    {
        // Arrange
        $cacheStorage = new InMemoryContextCacheStorage();
        $context = new Context();
        $cacheStorage->set($context);

        // Act
        $actualContext = $cacheStorage->get($context->getName());

        // Assert
        $this->assertSame($context, $actualContext);
    }

    /**
     * @return void
     */
    public function testGetReturnsNullIfContextNotFound(): void
    {
        // Arrange
        $cacheStorage = new InMemoryContextCacheStorage();

        // Act
        $context = $cacheStorage->get('test');

        // Assert
        $this->assertNull($context);
    }

    /**
     * @return void
     */
    public function testGetAll(): void
    {
        // Arrange
        $cacheStorage = new InMemoryContextCacheStorage();

        // Act
        $data = $cacheStorage->getAll();

        // Assert
        $this->assertSame([], $data);
    }

    /**
     * @return void
     */
    public function testRemove(): void
    {
        // Arrange
        $cacheStorage = new InMemoryContextCacheStorage();
        $contextShouldntBeRemoved = new Context();
        $contextShouldntBeRemoved->setName('exist');
        $contextShouldBeRemoved = new Context();
        $contextShouldBeRemoved->setName('removed');
        $cacheStorage->set($contextShouldBeRemoved);
        $cacheStorage->set($contextShouldntBeRemoved);

        // Act
        $cacheStorage->remove($contextShouldBeRemoved);

        // Assert
        $this->assertNotNull($cacheStorage->get($contextShouldntBeRemoved->getName()));
        $this->assertNull($cacheStorage->get($contextShouldBeRemoved->getName()));
    }

    /**
     * @return void
     */
    public function testSetContextWithTheSameNameOverwritesPrevious(): void
    {
        // Arrange
        $cacheStorage = new InMemoryContextCacheStorage();
        $contextOne = new Context();
        $contextOne->setName('test');
        $contextOne->setFormat('json');
        $cacheStorage->set($contextOne);

        $contextTwo = new Context();
        $contextTwo->setName('test');
        $contextTwo->setFormat('yaml');
        $cacheStorage->set($contextTwo);

        // Act
        $actualContext = $cacheStorage->get($contextOne->getName());

        // Assert
        $this->assertSame($contextTwo, $actualContext);
    }
}
