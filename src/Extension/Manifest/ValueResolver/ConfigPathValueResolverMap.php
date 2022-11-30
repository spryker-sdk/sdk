<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Manifest\ValueResolver;

use SprykerSdk\Sdk\Extension\ValueResolver\ConfigPathValueResolver;

class ConfigPathValueResolverMap extends OriginValueResolverMap
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return ConfigPathValueResolver::RESOLVER_ID;
    }

    /**
     * @return array<mixed, \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig>
     */
    public function getMap(): array
    {
        $valueResolverMap = parent::getMap();

        unset($valueResolverMap[static::SETTING_PATHS_KEY]);

        return $valueResolverMap;
    }
}
