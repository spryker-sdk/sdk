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
        $missedItemName = 'two';

        // Act
        $itemOneStatus = $registry->has('one');
        $itemTwoStatus = $registry->has($missedItemName);

        // Assert
        $this->assertTrue($itemOneStatus, sprintf('Item `%s` should be in registry', $item->getName()));
        $this->assertFalse($itemTwoStatus, sprintf('Item `%s` shouldn\'t be in registry', $missedItemName));
    }

    /**
     * @return void
     */
    public function testFindShouldReturnItemByName(): void
    {
        // Arrange
        $item = $this->createRegistryItem('one');
        $registry = new ValuesResolverMapRegistry([$item]);
        $missedItemName = 'two';

        // Act
        $itemOne = $registry->find('one');
        $itemTwo = $registry->find($missedItemName);

        // Assert
        $this->assertSame($item, $itemOne, sprintf('Item `%s` should be fount in registry', $item->getName()));
        $this->assertNull($itemTwo, sprintf('Item `%s` shouldn\'t be in registry', $missedItemName));
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
