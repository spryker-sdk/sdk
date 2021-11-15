<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\Repository;

use SprykerSdk\Sdk\Core\Domain\Entity\Setting;
use SprykerSdk\Sdk\Core\Domain\Repository\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    public function __construct(
        protected string $sdkBasePath
    ) {
    }

    public function findByPath(string $settingPath): ?Setting
    {
        //@todo implement properly
        return (new Setting(
            'task_dirs',
            $this->sdkBasePath . '/Tasks',
            'merge',
            null,
            true
        ));
    }

    public function save(Setting $setting): Setting
    {
        // TODO: Implement save() method.
    }

}