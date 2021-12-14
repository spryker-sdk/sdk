<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Domain\Entity;

use SprykerSdk\SdkContracts\Entity\SettingInterface;

class Setting implements SettingInterface
{
    /**
     * @var string
     */
    protected string $path;

    /**
     * @var mixed
     */
    protected mixed $values;

    /**
     * @var string
     */
    protected string $strategy = self::STRATEGY_REPLACE;

    /**
     * @var string
     */
    protected string $type = 'string';

    /**
     * @var bool
     */
    protected bool $isProject = true;

    /**
     * @var bool
     */
    protected bool $hasInitialization = false;

    /**
     * @var string|null
     */
    protected ?string $initializationDescription = null;

    /**
     * @var string|null
     */
    protected ?string $initializer = null;

    /**
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     * @param string $type
     * @param bool $isProject
     * @param bool $hasInitialization
     * @param string|null $initializationDescription
     * @param string|null $initializer
     */
    public function __construct(
        string $path,
        mixed $values,
        string $strategy,
        string $type = 'string',
        bool $isProject = true,
        bool $hasInitialization = false,
        ?string $initializationDescription = null,
        ?string $initializer = null
    ) {
        $this->initializationDescription = $initializationDescription;
        $this->hasInitialization = $hasInitialization;
        $this->isProject = $isProject;
        $this->type = $type;
        $this->strategy = $strategy;
        $this->values = $values;
        $this->path = $path;
        $this->initializer = $initializer;
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
     *
     * @return void
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
    public function hasInitialization(): bool
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

    /**
     * @return string|null
     */
    public function getInitializer(): ?string
    {
        return $this->initializer;
    }
}
