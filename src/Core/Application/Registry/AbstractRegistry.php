<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Registry;

use InvalidArgumentException;

/**
 * @template T of \SprykerSdk\Sdk\Core\Application\Registry\RegistryItemInterface
 * @implements \SprykerSdk\Sdk\Core\Application\Registry\RegistryInterface<T>
 */
abstract class AbstractRegistry implements RegistryInterface
{
    /**
     * @var bool
     */
    protected bool $isLoaded = false;

    /**
     * @var iterable<T>
     */
    protected iterable $iterableItems;

    /**
     * @var array<string, T>
     */
    protected array $items;

    /**
     * @param iterable<T> $iterableItems
     */
    public function __construct(iterable $iterableItems)
    {
        $this->iterableItems = $iterableItems;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        $this->load();

        return array_key_exists($name, $this->items);
    }

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return T
     */
    public function get(string $name): RegistryItemInterface
    {
        $item = $this->find($name);

        if ($item === null) {
            throw new InvalidArgumentException(sprintf('Service with name `%s` not found', $name));
        }

        return $item;
    }

    /**
     * @param string $name
     *
     * @return T|null
     */
    public function find(string $name): ?RegistryItemInterface
    {
        $this->load();

        return $this->items[$name] ?? null;
    }

    /**
     * @return array<string>
     */
    public function getNames(): array
    {
        $this->load();

        return array_keys($this->items);
    }

    /**
     * @return void
     */
    protected function load(): void
    {
        if ($this->isLoaded) {
            return;
        }

        foreach ($this->iterableItems as $iterableItem) {
            $this->items[$iterableItem->getName()] = $iterableItem;
        }

        $this->isLoaded = true;
    }
}
