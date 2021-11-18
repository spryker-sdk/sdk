<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Sdk\Task\ValueResolver\Value;

class ProjectDirValueResolver implements ValueResolverInterface
{
    /**
     * @return string|null
     */
    public function getParameterName(): ?string
    {
        return 'project_dir';
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'PROJECT_DIR';
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Base path to the project directory';
    }

    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function getValue(array $settings)
    {
        return null;
    }

    /**
     * @return array<string>
     */
    public function getSettingPaths(): array
    {
        return [
            'project_dir',
        ];
    }

    /**
     * E.g.: string, bool, int, path
     *
     * @return string
     */
    public function getType(): string
    {
        return 'path';
    }
}
