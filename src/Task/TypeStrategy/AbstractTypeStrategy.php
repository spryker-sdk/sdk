<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\TypeStrategy;

abstract class AbstractTypeStrategy implements TypeStrategyInterface
{
    /**
     * @var array
     */
    protected array $definition;

    /**
     * @param array $definition
     *
     * @return $this
     */
    public function setDefinition(array $definition): self
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return array
     */
    abstract public function extract(): array;

    /**
     * @param array $definition
     *
     * @return string
     */
    abstract public function execute(array $definition): string;
}
