<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Presentation\Console\Manifest\Task;

use SprykerSdk\Sdk\Core\Application\Dto\ReceiverValue as Config;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\ReceivedValue;
use SprykerSdk\Sdk\Infrastructure\Manifest\Interaction\Config\StaticValue;
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
    protected string $defaultYamlTaskDestinationDir;

    /**
     * @var string
     */
    protected string $defaultPhpTaskDestinationDir;

    /**
     * @param string $defaultYamlTaskDestinationDir
     * @param string $defaultPhpTaskDestinationDir
     */
    public function __construct(string $defaultYamlTaskDestinationDir, string $defaultPhpTaskDestinationDir)
    {
        $this->defaultYamlTaskDestinationDir = $defaultYamlTaskDestinationDir;
        $this->defaultPhpTaskDestinationDir = $defaultPhpTaskDestinationDir;
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
                new Config('Task file name', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::ID_KEY => new ReceivedValue(
                new Config('Task id', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::SHORT_DESCRIPTION_KEY => new ReceivedValue(
                new Config('Task short description', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::VERSION_KEY => new ReceivedValue(
                new Config('Task version', '0.1.0', ValueTypeEnum::TYPE_STRING),
            ),
            static::COMMAND_KEY => new ReceivedValue(
                new Config('Task command', null, ValueTypeEnum::TYPE_STRING),
            ),
            static::TYPE_KEY => new ReceivedValue(
                new Config(
                    'Task type',
                    null,
                    ValueTypeEnum::TYPE_STRING,
                    [Task::TYPE_LOCAL_CLI, Task::TYPE_LOCAL_CLI_INTERACTIVE],
                ),
            ),
        ];
    }
}
