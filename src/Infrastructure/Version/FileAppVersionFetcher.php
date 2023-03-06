<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Version;

use InvalidArgumentException;
use SprykerSdk\Sdk\Core\Application\Dependency\AppVersionFetcherInterface;

class FileAppVersionFetcher implements AppVersionFetcherInterface
{
    /**
     * @var string
     */
    protected const VERSION_FILE_NAME = 'VERSION';

    /**
     * @var string
     */
    protected string $projectDir;

    /**
     * @param string $projectDir
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function fetchAppVersion(): string
    {
        $versionFile = $this->projectDir . DIRECTORY_SEPARATOR . static::VERSION_FILE_NAME;

        if (!is_file($versionFile)) {
            throw new InvalidArgumentException(sprintf('Version file `%s` does not exist', $versionFile));
        }

        return trim((string)file_get_contents($versionFile));
    }
}
