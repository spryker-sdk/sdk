<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Infrastructure\Builder\Yaml;

use Codeception\Module;

class YamlDataHelper extends Module
{
    /**
     * @return array<string, array>
     */
    public function createFilesData(): array
    {
        return [
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
        ];
    }

    /**
     * @return array<string, array>
     */
    public function createLifecycleCommandsData(): array
    {
        return [
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
        ];
    }
}
