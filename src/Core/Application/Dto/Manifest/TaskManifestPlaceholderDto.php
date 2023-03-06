<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\Manifest;

class TaskManifestPlaceholderDto
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
     * @var bool
     */
    protected bool $optional;

    /**
     * @var array
     */
    protected array $configuration;

    /**
     * @param string $name
     * @param string $valueResolver
     * @param bool $optional
     * @param array $configuration
     */
    public function __construct(string $name, string $valueResolver, bool $optional, array $configuration)
    {
        $this->name = $name;
        $this->valueResolver = $valueResolver;
        $this->optional = $optional;
        $this->configuration = $configuration;
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
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
