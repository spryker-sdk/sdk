<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Placeholder implements PlaceholderInterface
{
    /**
     * @param string $name
     * @param string $valueResolver
     * @param bool $isOptional
     */
    public function __construct(
        protected string $name,
        protected string $valueResolver,
        protected array $configuration = [],
        protected bool $isOptional = false
    ){
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValueResolver(): string
    {
        return $this->valueResolver;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->isOptional;
    }
}