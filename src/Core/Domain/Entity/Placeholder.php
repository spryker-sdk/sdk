<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\PlaceholderInterface;

class Placeholder implements PlaceholderInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $valueResolver;

    /**
     * @var array
     */
    protected array $configuration = [];

    /**
     * @var bool
     */
    protected bool $isOptional = false;

    /**
     * @param string $name
     * @param string $valueResolver
     * @param array $configuration
     * @param bool $isOptional
     */
    public function __construct(
        string $name,
        string $valueResolver,
        array $configuration = [],
        bool $isOptional = false
    ) {
        $this->isOptional = $isOptional;
        $this->configuration = $configuration;
        $this->valueResolver = $valueResolver;
        $this->name = $name;
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
