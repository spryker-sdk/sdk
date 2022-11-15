<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Extension\Manifest\ValueResolver;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue as Config;
use SprykerSdk\Sdk\Extension\ValueResolver\OriginValueResolver;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ReceivedValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ValueCollection;
use SprykerSdk\Sdk\Presentation\Console\Manifest\Task\ValueResolver\ValueResolverMapInterface;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class OriginValueResolverMap implements ValueResolverMapInterface
{
    /**
     * @var string
     */
    public const DESCRIPTION_PREFIX = 'Value resolver';

    /**
     * @var string
     */
    public const ALIAS_KEY = 'alias';

    /**
     * @var string
     */
    public const DESCRIPTION_KEY = 'description';

    /**
     * @var string
     */
    public const OPTION_KEY = 'option';

    /**
     * @var string
     */
    public const DEFAULT_VALUE_KEY = 'defaultValue';

    /**
     * @var string
     */
    public const TYPE_KEY = 'type';

    /**
     * @var string
     */
    public const SETTING_PATHS_KEY = 'settingPaths';

    /**
     * @var string
     */
    public const CHOICE_VALUES_KEY = 'choiceValues';

    /**
     * @var string
     */
    public const COLLECTION_ITEM_KEY = 'value';

    /**
     * @return array<mixed, \SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig>
     */
    public function getMap(): array
    {
        return [
            static::ALIAS_KEY => new ReceivedValue(
                new Config(
                    static::ALIAS_KEY,
                    sprintf('%s %s', static::DESCRIPTION_PREFIX, static::ALIAS_KEY),
                    null,
                    ValueTypeEnum::TYPE_STRING,
                ),
                false,
            ),
            static::DESCRIPTION_KEY => new ReceivedValue(
                new Config(
                    static::DESCRIPTION_KEY,
                    sprintf('%s %s', static::DESCRIPTION_PREFIX, static::DESCRIPTION_KEY),
                    null,
                    ValueTypeEnum::TYPE_STRING,
                ),
                false,
            ),
            static::OPTION_KEY => new ReceivedValue(
                new Config(
                    static::OPTION_KEY,
                    sprintf('%s %s', static::DESCRIPTION_PREFIX, static::OPTION_KEY),
                    null,
                    ValueTypeEnum::TYPE_STRING,
                ),
                false,
            ),
            static::DEFAULT_VALUE_KEY => new ReceivedValue(
                new Config(
                    static::DEFAULT_VALUE_KEY,
                    sprintf('%s %s', static::DESCRIPTION_PREFIX, static::DEFAULT_VALUE_KEY),
                    null,
                    ValueTypeEnum::TYPE_STRING,
                ),
                false,
            ),
            static::TYPE_KEY => new ReceivedValue(
                new Config(
                    static::TYPE_KEY,
                    sprintf('%s %s', static::DESCRIPTION_PREFIX, static::TYPE_KEY),
                    null,
                    ValueTypeEnum::TYPE_STRING,
                    ValueTypeEnum::getAllValueTypes(),
                ),
                false,
            ),
            static::SETTING_PATHS_KEY => new ValueCollection(
                [
                    new ReceivedValue(
                        new Config(
                            static::COLLECTION_ITEM_KEY,
                            'Setting path value',
                            null,
                            ValueTypeEnum::TYPE_STRING,
                            ValueTypeEnum::getAllValueTypes(),
                        ),
                    ),
                ],
                false,
                true,
            ),
            static::CHOICE_VALUES_KEY => new ValueCollection(
                [
                    new ReceivedValue(
                        new Config(
                            static::COLLECTION_ITEM_KEY,
                            'Choice value',
                            null,
                            ValueTypeEnum::TYPE_STRING,
                        ),
                    ),
                ],
                false,
                true,
            ),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return OriginValueResolver::RESOLVER_ID;
    }
}
