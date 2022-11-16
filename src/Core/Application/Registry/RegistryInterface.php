<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Registry;

/**
 * @template T of \SprykerSdk\Sdk\Core\Application\Registry\RegistryItemInterface
 */
interface RegistryInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return T
     */
    public function get(string $name): RegistryItemInterface;

    /**
     * @return array<string, T>
     */
    public function getAll(): array;

    /**
     * @param string $name
     *
     * @return T|null
     */
    public function find(string $name): ?RegistryItemInterface;

    /**
     * @return array<string>
     */
    public function getNames(): array;
}
