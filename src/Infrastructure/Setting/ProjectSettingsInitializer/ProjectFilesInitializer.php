<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer;

use Symfony\Component\Filesystem\Filesystem;

class ProjectFilesInitializer
{
    /**
     * @var string
     */
    protected string $projectSettingFileName;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected Filesystem $filesystem;

    /**
     * @param string $projectSettingFileName
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     */
    public function __construct(string $projectSettingFileName, Filesystem $filesystem)
    {
        $this->projectSettingFileName = $projectSettingFileName;
        $this->filesystem = $filesystem;
    }

    /**
     * @return bool
     */
    public function isProjectSettingsInitialised(): bool
    {
        return $this->filesystem->exists($this->projectSettingFileName);
    }

    /**
     * @return void
     */
    public function initProjectFiles(): void
    {
        $this->createGitignore();
    }

    /**
     * @return void
     */
    protected function createGitignore(): void
    {
        $settingsDir = dirname($this->projectSettingFileName);
        $ignoreRules = [
            '*',
            '!.gitignore',
            '!' . basename($this->projectSettingFileName),
        ];

        if (realpath($settingsDir) !== realpath('.')) {
            $this->filesystem->dumpFile(
                sprintf('%s/.gitignore', $settingsDir),
                implode(PHP_EOL, $ignoreRules),
            );
        }
    }
}
