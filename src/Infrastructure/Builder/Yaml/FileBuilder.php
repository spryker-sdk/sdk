<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Builder\Yaml;

use SprykerSdk\Sdk\Core\Domain\Entity\File;

class FileBuilder implements FileBuilderInterface
{
    /**
     * @param array $data
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\FileInterface>
     */
    public function buildFiles(array $data): array
    {
        $files = [];

        if (!isset($data['files'])) {
            return $files;
        }

        foreach ($data['files'] as $file) {
            $files[] = new File($file['path'], $file['content']);
        }

        return $files;
    }
}
