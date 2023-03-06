<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config;

class ValueCollection implements InteractionValueConfig
{
    /**
     * @var array<(array|\SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig)>
     */
    protected array $valueConfigs;

    /**
     * @var bool
     */
    protected bool $minOneItemRequired;

    /**
     * @var bool
     */
    protected bool $isFlatList;

    /**
     * @param array<(array|\SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig)> $valueConfigs
     * @param bool $minOneItemRequired
     * @param bool $isFlatList
     */
    public function __construct(array $valueConfigs, bool $minOneItemRequired = false, bool $isFlatList = false)
    {
        $this->valueConfigs = $valueConfigs;
        $this->minOneItemRequired = $minOneItemRequired;
        $this->isFlatList = $isFlatList;
    }

    /**
     * @return array<(array|\SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig)>
     */
    public function getValueConfigs(): array
    {
        return $this->valueConfigs;
    }

    /**
     * @return bool
     */
    public function isMinOneItemRequired(): bool
    {
        return $this->minOneItemRequired;
    }

    /**
     * @return bool
     */
    public function isFlatList(): bool
    {
        return $this->isFlatList;
    }
}
