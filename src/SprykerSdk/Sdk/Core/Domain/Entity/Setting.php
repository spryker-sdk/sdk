<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

class Setting implements SettingInterface
{
    /**
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     * @param string $type
     * @param bool $isProject
     * @param bool $hasInitialization
     * @param string|null $initializationDescription
     */
    public function __construct(
        protected string   $path,
        protected mixed    $values,
        protected string   $strategy,
        protected string   $type = 'string',
        protected bool $isProject = true,
        protected bool     $hasInitialization = false,
        protected ?string $initializationDescription = null
    ) {
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getValues(): mixed
    {
        return $this->values;
    }

    /**
     * @param mixed $values
     */
    public function setValues(mixed $values): void
    {
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getStrategy(): string
    {
        return $this->strategy;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isProject(): bool
    {
        return $this->isProject;
    }

    /**
     * @return bool
     */
    public function isHasInitialization(): bool
    {
        return $this->hasInitialization;
    }

    /**
     * @return string|null
     */
    public function getInitializationDescription(): ?string
    {
        return $this->initializationDescription;
    }
}