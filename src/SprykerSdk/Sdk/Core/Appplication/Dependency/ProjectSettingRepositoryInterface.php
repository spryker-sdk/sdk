<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Core\Appplication\Dependency;

use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;

interface ProjectSettingRepositoryInterface extends SettingRepositoryInterface
{
    /**
     * @param array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting> $settings
     *
     * @return array<\SprykerSdk\Sdk\Core\Domain\Entity\Setting>
     */
    public function saveMultiple(array $settings): array;
}
