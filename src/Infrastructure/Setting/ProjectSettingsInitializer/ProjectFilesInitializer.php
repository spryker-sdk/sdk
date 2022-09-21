<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Setting\ProjectSettingsInitializer;

class ProjectFilesInitializer
{
    /**
     * @var string
     */
    protected string $projectSettingFileName;

    /**
     * @param string $projectSettingFileName
     */
    public function __construct(string $projectSettingFileName)
    {
        $this->projectSettingFileName = $projectSettingFileName;
    }

    /**
     * @return bool
     */
    public function isProjectSettingsInitialised(): bool
    {
        return is_file($this->projectSettingFileName);
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
            file_put_contents(
                sprintf('%s/.gitignore', $settingsDir),
                implode(PHP_EOL, $ignoreRules),
            );
        }
    }
}
