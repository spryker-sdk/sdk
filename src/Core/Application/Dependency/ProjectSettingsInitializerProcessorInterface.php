<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Application\Dependency;

use SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto;

interface ProjectSettingsInitializerProcessorInterface
{
    /**
     * @param array<\SprykerSdk\SdkContracts\Entity\SettingInterface> $settings
     * @param \SprykerSdk\Sdk\Core\Application\Dto\ProjectSettingsInitDto $projectSettingsDto
     *
     * @return array<\SprykerSdk\SdkContracts\Entity\SettingInterface>
     */
    public function initialize(array $settings, ProjectSettingsInitDto $projectSettingsDto): array;

    /**
     * @return bool
     */
    public function isInitialized(): bool;
}
