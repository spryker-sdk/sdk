<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Entity;

use SprykerSdk\Sdk\Core\Domain\Entity\Setting as DomainSetting;

class Setting extends DomainSetting
{
    /**
     * @param int|null $id
     * @param string $path
     * @param mixed $values
     * @param string $strategy
     * @param string $type
     * @param bool $isProject
     * @param bool $hasInitialization
     * @param string|null $initializationDescription
     */
    public function __construct(
        protected ?int $id,
        string $path,
        mixed $values,
        string $strategy,
        string $type = 'string',
        bool $isProject = true,
        bool $hasInitialization = false,
        ?string $initializationDescription = null
    ) {
        parent::__construct($path, $values, $strategy, $type, $isProject, $hasInitialization, $initializationDescription);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param bool $hasInitialization
     */
    public function setHasInitialization(bool $hasInitialization): void
    {
        $this->hasInitialization = $hasInitialization;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @param string $strategy
     */
    public function setStrategy(string $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param bool $isProject
     */
    public function setIsProject(bool $isProject): void
    {
        $this->isProject = $isProject;
    }

    /**
     * @param string|null $initializationDescription
     */
    public function setInitializationDescription(?string $initializationDescription): void
    {
        $this->initializationDescription = $initializationDescription;
    }
}
