<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dto\SdkInit;

class InitializeCriteriaDto
{
    /**
     * @var string
     */
    protected string $sourceType = '';

    /**
     * @var array
     */
    protected array $settings = [];

    /**
     * @param string $sourceType
     * @param array $settings
     */
    public function __construct(string $sourceType, array $settings = [])
    {
        $this->sourceType = $sourceType;
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return (bool)$this->settings;
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }
}
