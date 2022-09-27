<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Infrastructure\Builder\Yaml;

use Codeception\Module;
use SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface;
use SprykerSdk\Sdk\Core\Domain\Enum\LifecycleName;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml;
use SprykerSdk\SdkContracts\Entity\ContextInterface;
use SprykerSdk\SdkContracts\Entity\TaskInterface;

class TaskYamlDataHelper extends Module
{
    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createFilesData(): TaskYaml
    {
        return new TaskYaml([
            'files' => [
                [
                    'path' => 'test/path1',
                    'content' => 'Dummy content1',
                ],
                [
                    'path' => 'test/path2',
                    'content' => 'Dummy content2',
                ],
            ],
        ], []);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createLifecycleCommandsData(): TaskYaml
    {
        return new TaskYaml([
            'commands' => [
                [
                    'command' => 'echo 1;',
                    'type' => 'cli',
                ],
                [
                    'command' => 'echo 2;',
                    'type' => 'cli',
                ],
            ],
        ], []);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createSingleCommandData(): TaskYaml
    {
        return new TaskYaml([
            'command' => 'echo 1',
            'type' => 'local_cli',
            'stop_on_error' => false,
            'tags' => ['default'],
            'stage' => ContextInterface::DEFAULT_STAGE,
        ], []);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createTaskSetData(): TaskYaml
    {
        return new TaskYaml([
            'tasks' => [
                [
                    'id' => 'task:1',
                    'stop_on_error' => false,
                    'tags' => ['default'],
                ],
                [
                    'id' => 'task:2',
                    'stop_on_error' => false,
                    'tags' => ['default'],
                ],
            ],
            'type' => 'task_set',
        ], [
            'task:1' => [
                'command' => 'echo 1',
                'type' => 'local_cli',
                'stop_on_error' => false,
                'tags' => ['default'],
                'stage' => ContextInterface::DEFAULT_STAGE,
            ],
            'task:2' => [
                'command' => 'echo 1',
                'type' => 'local_cli',
                'stop_on_error' => false,
                'tags' => ['default'],
                'stage' => ContextInterface::DEFAULT_STAGE,
            ],
        ]);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createLifecycleEventData(): TaskYaml
    {
        return new TaskYaml([
            'lifecycle' => [
                LifecycleName::INITIALIZED => [
                    'commands' => [
                        [
                            'command' => 'echo test',
                            'type' => 'local_cli',
                        ],
                    ],
                    'placeholders' => [
                        [
                            'name' => 'world',
                            'value_resolver' => 'STATIC',
                            'configuration' => [],
                            'optional' => true,
                        ],
                    ],
                    'files' => [
                        [
                            'path' => '/test/path',
                            'content' => 'Hello %world%!',
                        ],
                    ],
                ],
                LifecycleName::REMOVED => [
                    'commands' => [
                        [
                            'command' => 'echo test',
                            'type' => 'local_cli',
                        ],
                    ],
                    'placeholders' => [
                        [
                            'name' => 'world',
                            'value_resolver' => 'STATIC',
                            'configuration' => [],
                            'optional' => true,
                        ],
                    ],
                    'files' => [
                        [
                            'path' => '/test/path',
                            'content' => 'Hello %world%!',
                        ],
                    ],
                ],
                LifecycleName::UPDATED => [
                    'commands' => [
                        [
                            'command' => 'echo test',
                            'type' => 'local_cli',
                        ],
                    ],
                    'placeholders' => [
                        [
                            'name' => 'world',
                            'value_resolver' => 'STATIC',
                            'configuration' => [],
                            'optional' => true,
                        ],
                    ],
                    'files' => [
                        [
                            'path' => '/test/path',
                            'content' => 'Hello %world%!',
                        ],
                    ],
                ],
            ],
        ], []);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createConverterData(): TaskYaml
    {
        return new TaskYaml([
            'report_converter' => [
                'name' => 'CheckstyleViolationReportConverter',
                'configuration' => [
                    'input_file' => 'phpcs.codestyle.json',
                    'producer' => 'phpcs',
                ],
            ],
        ], []);
    }

    /**
     * @param string $lifecycleName
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     * @param \SprykerSdk\Sdk\Core\Domain\Entity\Lifecycle\LifecycleEventDataInterface $eventData
     *
     * @return void
     */
    public function assertLifecycleEventData(string $lifecycleName, TaskYaml $taskYaml, LifecycleEventDataInterface $eventData): void
    {
        $expectedCommandsData = $taskYaml->getTaskData()['lifecycle'][$lifecycleName]['commands'];
        $expectedFilesData = $taskYaml->getTaskData()['lifecycle'][$lifecycleName]['files'];
        $expectedPlaceholdersData = $taskYaml->getTaskData()['lifecycle'][$lifecycleName]['placeholders'];

        $this->assertSame($expectedCommandsData[0]['command'], $eventData->getCommands()[0]->getCommand());
        $this->assertSame($expectedCommandsData[0]['type'], $eventData->getCommands()[0]->getType());

        $this->assertSame($expectedFilesData[0]['content'], $eventData->getFiles()[0]->getContent());
        $this->assertSame($expectedFilesData[0]['path'], $eventData->getFiles()[0]->getPath());

        $this->assertSame($expectedPlaceholdersData[0]['name'], $eventData->getPlaceholders()['world']->getName());
        $this->assertSame($expectedPlaceholdersData[0]['configuration'], $eventData->getPlaceholders()['world']->getConfiguration());
        $this->assertSame($expectedPlaceholdersData[0]['value_resolver'], $eventData->getPlaceholders()['world']->getValueResolver());
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createPlaceholdersData(): TaskYaml
    {
        return new TaskYaml([
            'placeholders' => [
                [
                    'name' => 'world',
                    'value_resolver' => 'STATIC',
                    'configuration' => [],
                    'optional' => true,
                ],
                [
                    'name' => 'hello',
                    'value_resolver' => 'STATIC',
                    'configuration' => [],
                    'optional' => true,
                ],
            ],
        ], []);
    }

    /**
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createTaskData(): TaskYaml
    {
        return new TaskYaml($this->createTaskArrayData(), []);
    }

    /**
     * @return array
     */
    public function createTaskArrayData(): array
    {
        return [
            'id' => 'task:id',
            'short_description' => 'Short description',
            'command' => 'echo 1',
            'type' => 'local_cli',
            'stop_on_error' => false,
            'tags' => ['default'],
            'stage' => ContextInterface::DEFAULT_STAGE,
            'version' => '1.1.1',
            'help' => 'Help!',
            'successor' => 'task:id:successor',
            'placeholders' => [
                [
                    'name' => 'world',
                    'value_resolver' => 'STATIC',
                    'configuration' => [],
                    'optional' => true,
                ],
                [
                    'name' => 'hello',
                    'value_resolver' => 'STATIC',
                    'configuration' => [],
                    'optional' => true,
                ],
            ],
        ];
    }

    /**
     * @param \SprykerSdk\SdkContracts\Entity\TaskInterface $subTask
     *
     * @return \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml
     */
    public function createTaskSetYamlData(TaskInterface $subTask): TaskYaml
    {
        return new TaskYaml(
            [
                'tasks' => [
                    [
                        'id' => $subTask->getId(),
                    ],
                ],
            ],
            [],
            [
                $subTask->getId() => $subTask,
            ],
        );
    }
}
