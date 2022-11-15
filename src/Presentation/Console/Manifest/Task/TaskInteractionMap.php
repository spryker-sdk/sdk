<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Manifest\Task;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue as Config;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\CallbackValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ReceivedValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\StaticValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ValueCollection;
use SprykerSdk\Sdk\Presentation\Console\Manifest\Task\ValueResolver\ValuesResolverMapRegistryInterface;
use SprykerSdk\SdkContracts\Enum\Task;
use SprykerSdk\SdkContracts\Enum\ValueTypeEnum;

class TaskInteractionMap
{
    /**
     * @var string
     */
    public const FILE_FORMAT_KEY = 'file_format';

    /**
     * @var string
     */
    public const FILE_NAME_KEY = 'file_name';

    /**
     * @var string
     */
    public const ID_KEY = 'id';

    /**
     * @var string
     */
    public const SHORT_DESCRIPTION_KEY = 'short_description';

    /**
     * @var string
     */
    public const VERSION_KEY = 'version';

    /**
     * @var string
     */
    public const COMMAND_KEY = 'command';

    /**
     * @var string
     */
    public const TYPE_KEY = 'type';

    /**
     * @var string
     */
    public const PLACEHOLDERS_KEY = 'placeholders';

    /**
     * @var string
     */
    public const PLACEHOLDER_NAME_KEY = 'name';

    /**
     * @var string
     */
    public const PLACEHOLDER_VALUE_RESOLVER_KEY = 'value_resolver';

    /**
     * @var string
     */
    public const PLACEHOLDER_OPTIONAL_KEY = 'optional';

    /**
     * @var string
     */
    public const PLACEHOLDER_CONFIGURATION_IS_NEEDED_KEY = 'is_configuration_needed';

    /**
     * @var string
     */
    public const PLACEHOLDER_CONFIGURATION_KEY = 'configuration';

    /**
     * @var string
     */
    protected string $defaultYamlTaskDestinationDir;

    /**
     * @var string
     */
    protected string $defaultPhpTaskDestinationDir;

    /**
     * @var \SprykerSdk\Sdk\Presentation\Console\Manifest\Task\ValueResolver\ValuesResolverMapRegistryInterface
     */
    protected ValuesResolverMapRegistryInterface $valuesResolverMapRegistry;

    /**
     * @param string $defaultYamlTaskDestinationDir
     * @param string $defaultPhpTaskDestinationDir
     * @param \SprykerSdk\Sdk\Presentation\Console\Manifest\Task\ValueResolver\ValuesResolverMapRegistryInterface $valuesResolverMapRegistry
     */
    public function __construct(
        string $defaultYamlTaskDestinationDir,
        string $defaultPhpTaskDestinationDir,
        ValuesResolverMapRegistryInterface $valuesResolverMapRegistry
    ) {
        $this->defaultYamlTaskDestinationDir = $defaultYamlTaskDestinationDir;
        $this->defaultPhpTaskDestinationDir = $defaultPhpTaskDestinationDir;
        $this->valuesResolverMapRegistry = $valuesResolverMapRegistry;
    }

    /**
     * @param array<string, mixed> $predefinedValues
     *
     * @return array
     */
    public function getInteractionMap(array $predefinedValues): array
    {
        return [
            static::FILE_FORMAT_KEY => new StaticValue(
                $predefinedValues[static::FILE_FORMAT_KEY],
            ),
            static::FILE_NAME_KEY => new ReceivedValue(
                new Config(static::FILE_NAME_KEY, 'Task file name', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::ID_KEY => new ReceivedValue(
                new Config(static::ID_KEY, 'Task id', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::SHORT_DESCRIPTION_KEY => new ReceivedValue(
                new Config(static::SHORT_DESCRIPTION_KEY, 'Task short description', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::VERSION_KEY => new ReceivedValue(
                new Config(static::VERSION_KEY, 'Task version', '0.1.0', ValueTypeEnum::TYPE_STRING),
            ),
            static::COMMAND_KEY => new ReceivedValue(
                new Config(static::COMMAND_KEY, 'Task command', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::TYPE_KEY => new ReceivedValue(
                new Config(
                    static::TYPE_KEY,
                    'Task type',
                    null,
                    ValueTypeEnum::TYPE_STRING,
                    [Task::TYPE_LOCAL_CLI, Task::TYPE_LOCAL_CLI_INTERACTIVE],
                ),
            ),
            static::PLACEHOLDERS_KEY => new ValueCollection($this->getPlaceholdersConfigs()),
        ];
    }

    /**
     * @return array<\SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\InteractionValueConfig>
     */
    protected function getPlaceholdersConfigs(): array
    {
        return [
            static::PLACEHOLDER_NAME_KEY => new ReceivedValue(
                new Config(static::PLACEHOLDER_NAME_KEY, 'Placeholder name', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::PLACEHOLDER_VALUE_RESOLVER_KEY => new ReceivedValue(
                new Config(
                    static::PLACEHOLDER_VALUE_RESOLVER_KEY,
                    'Placeholder value resolver',
                    null,
                    ValueTypeEnum::TYPE_STRING,
                    $this->valuesResolverMapRegistry->getNames(),
                ),
            ),
            static::PLACEHOLDER_OPTIONAL_KEY => new ReceivedValue(
                new Config(static::PLACEHOLDER_NAME_KEY, 'Is placeholder optional?', true, ValueTypeEnum::TYPE_BOOL),
            ),
            static::PLACEHOLDER_CONFIGURATION_IS_NEEDED_KEY => new ReceivedValue(
                new Config(static::PLACEHOLDER_NAME_KEY, 'Would you like to add config into the placeholder', true, ValueTypeEnum::TYPE_BOOL),
            ),
            static::PLACEHOLDER_CONFIGURATION_KEY => new CallbackValue(
                function (array $receivedValues) {
                    $lastPlaceholder = end($receivedValues[static::PLACEHOLDERS_KEY]);

                    if (!$lastPlaceholder[static::PLACEHOLDER_CONFIGURATION_IS_NEEDED_KEY]) {
                        return [];
                    }

                    $lastResolverId = $lastPlaceholder[static::PLACEHOLDER_VALUE_RESOLVER_KEY];

                    return $this->valuesResolverMapRegistry->get($lastResolverId)->getMap();
                },
            ),
        ];
    }
}
