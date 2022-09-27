<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\File;
use SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml;

class FileCollectionBuilder
{
    /**
     * @param \SprykerSdk\Sdk\Infrastructure\Dto\TaskYaml\TaskYaml $taskYaml
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\FileInterface>
     */
    public function buildFiles(TaskYaml $taskYaml): array
    {
        $files = [];
        $data = $taskYaml->getTaskData();

        if (!isset($data['files'])) {
            return $files;
        }

        foreach ($data['files'] as $file) {
            $files[] = new File($file['path'], $file['content']);
        }

        return $files;
    }
}
