<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Unit\Presentation\Console\Manifest\Task\ValueResolver;

use Codeception\Test\Unit;
use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Registry\RegistryItemInterface;
use SprykerSdk\Sdk\Presentation\Console\Manifest\Task\ValueResolver\ValuesResolverMapRegistry;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Presentation
 * @group Console
 * @group Manifest
 * @group Task
 * @group ValueResolver
 * @group ValuesResolverMapRegistryTest
 * Add your own group annotations below this line
 */
class ValuesResolverMapRegistryTest extends Unit
{
    /**
     * @return void
     */
    public function testHasShouldCheckExistenceOfRegistryItem(): void
    {
        // Arrange
        $item = $this->createRegistryItem('one');
        $registry = new ValuesResolverMapRegistry([$item]);

        // Act
        $itemOneStatus = $registry->has('one');
        $itemTwoStatus = $registry->has('two');

        // Assert
        $this->assertTrue($itemOneStatus);
        $this->assertFalse($itemTwoStatus);
    }

    /**
     * @return void
     */
    public function testFindShouldReturnItemByName(): void
    {
        // Arrange
        $item = $this->createRegistryItem('one');
        $registry = new ValuesResolverMapRegistry([$item]);

        // Act
        $itemOne = $registry->find('one');
        $itemTwo = $registry->find('two');

        // Assert
        $this->assertSame($item, $itemOne);
        $this->assertNull($itemTwo);
    }

    /**
     * @return void
     */
    public function testGetShouldThrowExceptionWhenItemNotFound(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);

        $item = $this->createRegistryItem('one');
        $registry = new ValuesResolverMapRegistry([$item]);

        // Act
        $registry->get('two');
    }

    /**
     * @return void
     */
    public function testGetShouldReturnWhenItemFound(): void
    {
        // Arrange
        $item = $this->createRegistryItem('one');
        $registry = new ValuesResolverMapRegistry([$item]);

        // Act
        $itemOne = $registry->find('one');

        // Assert
        $this->assertSame($item, $itemOne);
    }

    /**
     * @return void
     */
    public function testGetAllShouldReturnAllItems(): void
    {
        // Arrange
        $itemOne = $this->createRegistryItem('one');
        $itemTwo = $this->createRegistryItem('two');
        $registry = new ValuesResolverMapRegistry([$itemOne, $itemTwo]);

        // Act
        $items = $registry->getAll();

        // Assert
        $this->assertSame(['one' => $itemOne, 'two' => $itemTwo], $items);
    }

    /**
     * @return void
     */
    public function testGetNamesShouldReturnAllItemNames(): void
    {
        // Arrange
        $itemOne = $this->createRegistryItem('one');
        $itemTwo = $this->createRegistryItem('two');
        $registry = new ValuesResolverMapRegistry([$itemOne, $itemTwo]);

        // Act
        $itemNames = $registry->getNames();

        // Assert
        $this->assertSame(['one', 'two'], $itemNames);
    }

    /**
     * @param string $name
     *
     * @return \SprykerSdk\Sdk\Core\Application\Registry\RegistryItemInterface
     */
    protected function createRegistryItem(string $name): RegistryItemInterface
    {
        $registryItem = $this->createMock(RegistryItemInterface::class);
        $registryItem->method('getName')->willReturn($name);

        return $registryItem;
    }
}
