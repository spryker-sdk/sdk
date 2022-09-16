<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface;
use SprykerSdk\Sdk\Core\Domain\Entity\File;

class FileCollectionBuilder implements FileCollectionBuilderInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\TaskYaml\TaskYamlInterface $taskYaml
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\FileInterface>
     */
    public function buildFiles(TaskYamlInterface $taskYaml): array
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
