<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Manifest\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue as Config;
use SprykerSdk\Sdk\Extension\ValueResolver\FlagValueResolver;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ReceivedValue;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class FlagValueResolverMap extends OriginValueResolverMap
{
    /**
     * @var string
     */
    public const FLAG_KEY = 'flag';

    /**
     * @return string
     */
    public function getName(): string
    {
        return FlagValueResolver::RESOLVER_ID;
    }

    /**
     * @return array<mixed, \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig>
     */
    public function getMap(): array
    {
        $valueResolverMap = parent::getMap();

        $valueResolverMap[static::FLAG_KEY] = new ReceivedValue(
            new Config(
                static::FLAG_KEY,
                sprintf('%s %s', static::DESCRIPTION_PREFIX, static::FLAG_KEY),
                null,
                ValueTypeEnum::TYPE_STRING,
            ),
            false,
        );

        unset($valueResolverMap[static::TYPE_KEY]);

        return $valueResolverMap;
    }
}
