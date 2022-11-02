<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Initializer;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;

interface ProjectSettingsInitializerInterface
{
    /**
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto $projectSettingsDto
     *
     * @return void
     */
    public function initialize(ProjectSettingsInitDto $projectSettingsDto): void;

    /**
     * @return bool
     */
    public function isProjectSettingsInitialised(): bool;
}
