<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Sdk\Infrastructure\TaskReader;

use SprykerSdk\Sdk\Core\Application\Dto\TaskCollection;
use SprykerSdk\SdkContracts\Entity\SettingInterface;

interface TaskReaderInterface
{
    /**
     * @param \SprykerSdk\SdkContracts\Entity\SettingInterface $taskDirSetting
     *
     * @return \SprykerSdk\Sdk\Core\Application\Dto\TaskCollection
     */
    public function read(SettingInterface $taskDirSetting): TaskCollection;
}
