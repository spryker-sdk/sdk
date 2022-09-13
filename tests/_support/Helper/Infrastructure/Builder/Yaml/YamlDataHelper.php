<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Tests\Helper\Infrastructure\Builder\Yaml;

use Codeception\Module;
use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml;

class YamlDataHelper extends Module
{
    /**
     * @return \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml
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
     * @return \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYaml
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
}
